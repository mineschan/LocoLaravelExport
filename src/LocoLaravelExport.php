<?php

namespace mineschan\LocoLaravelExport;


use Chumper\Zipper\Facades\Zipper;
use GuzzleHttp\Command\Exception\CommandClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Loco\Http\ApiClient;

class LocoLaravelExport
{
    protected $exportLocales = [];

    protected $consoleInstance = [];

    private $saveStorage;
    private $downloadStorage;

    private function setExportLocales($locales)
    {
        $this->exportLocales = collect($locales)->map(function ($locale) {
            return str_replace('_', '-', trim($locale));
        });
    }

    private function getDownloadStorage()
    {
        if ($this->downloadStorage) return $this->downloadStorage;

        $disk = config('loco-laravel-export.download.disk');
        $driver = config("filesystems.disks.{$disk}.driver");

        if ($driver != 'local') {
            throw new \Exception('Sorry! Only export to disk with driver = local is allowed. Your configured disk: ' . $disk);
        }

        $this->downloadStorage = Storage::disk($disk);

        return $this->downloadStorage;
    }

    private function getSaveStorage()
    {
        if ($this->saveStorage) return $this->saveStorage;

        $savePath = config('loco-laravel-export.export.destination');
        $this->saveStorage = Storage::createLocalDriver(['root' => $savePath]);
        return $this->saveStorage;
    }

    private function getSavePath()
    {
        return $this->getSaveStorage()->getAdapter()->getPathPrefix();
    }

    public static function exportArchive(array $locales = null, Command $console = null)
    {
        $static = new static();
        $static->setExportLocales($locales);
        $static->consoleInstance = $console;

        try {
            //check Api Key
            $apiKey = config('loco-laravel-export.api_key');

            if (!$apiKey) {
                throw new \Exception('Loco Api Key missing! Ensure you have the LOCO_EXPORT_API_KEY in .env file.');
            }

            $client = ApiClient::factory(['key' => $apiKey]);

            //Export translations archive using loco official SDK
            $console->info('Download in progress...');

            $result = $client->exportArchive(
                [
                    'ext' => 'php',
                    'format' => 'symfony'
                ]
            );

            $file = $result->getZip()->filename;

            if (!$file) {
                throw new \Exception('Archive download failed.');
            }

            $folder = config('loco-laravel-export.download.directory');
            $diskPath = $static->getDownloadStorage()->getAdapter()->getPathPrefix() . $folder;

            if ($zipper = Zipper::make($file)) {
                $console->comment('Archive downloaded, unzipping...');
                $zipper->extractTo($diskPath);
            }

            $projectFolder = $static->getDownloadStorage()->directories($folder);
            $translationFiles = $static->getDownloadStorage()->files($projectFolder[0] . '/Resources/translations');

            if (!$translationFiles) {

                $console->warn('Export ended with no translations');
                return;

            } else {

                collect($translationFiles)
                    ->flatMap(function ($file) {
                        $fileComponents = explode('.', basename($file));
                        $fileLocaleCode = collect($fileComponents)->get(count($fileComponents) - 2);
                        return [$fileLocaleCode => $file];
                    })
                    ->each(function ($filePath, $locale) use ($static) {
                        //save downloaded language file to laravel system
                        if ($static->isSaveNecessary($locale)) {
                            $static->saveContent($filePath, $locale);
                        }
                    });
            }

        } catch (CommandClientException  $e) {

            if ($e->getCode() == 401) {
                $console->error('[LocoLaravelExport] Request results in 401, check your Loco Api Key.');
            } else {
                $console->error('[LocoLaravelExport] Download error. Response code: ' . $e->getCode());
            }

        } catch (\Exception $e) {
            $console->error('[LocoLaravelExport] ' . $e->getMessage());
        }

        $static->cleanUp();

    }

    private function isSaveNecessary($locale)
    {
        if (!count($this->exportLocales)) return true;

        foreach ($this->exportLocales as $exportLocale) {
            if (Str::contains($locale, $exportLocale)) return true;
        }
    }

    private function saveContent($filePath, $locale)
    {
        $filename = config('loco-laravel-export.lang_filename') . '.php';
        $content = $this->getDownloadStorage()->get($filePath);

        $locale = str_replace('_', '-', $locale);

        $saved = $this->getSaveStorage()->put($locale . '/' . $filename, $content);
        if ($saved) $this->consoleInstance->line(sprintf('Language <comment>%s</comment> saved to <info>%s%s/%s</info>', $locale, $this->getSavePath(), $locale, $filename));
    }

    private function cleanUp()
    {
        if (config('loco-laravel-export.download.cleanup')) {
            $folder = config('loco-laravel-export.download.directory');
            $deleted = $this->getDownloadStorage()->deleteDirectory($folder);

            if ($deleted) $this->consoleInstance->comment('Downloaded folder cleaned');
        }
    }

}
