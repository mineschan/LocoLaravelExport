<?php

namespace mineschan\LocoLaravelExport;


use Chumper\Zipper\Facades\Zipper;
use GuzzleHttp\Command\Exception\CommandClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Loco\Http\ApiClient;

class LocoLaravelExport
{
//    public function __construct()
//    {
//        dd('test');
//    }

    public static function exportArchive(array $languages = null, Command $console = null)
    {
        $static = new static();

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

            $disk = config('loco-laravel-export.export_disk');
            $driver = config("filesystems.disks.{$disk}.driver");

            if ($driver != 'local') {
                throw new \Exception('Sorry! Only export to disk with driver = local is allowed. Your configured disk: ' . $disk);
            }

            $folder = config('loco-laravel-export.export_folder');

            $storage = Storage::disk($disk);
            $diskPath = $storage->getAdapter()->getPathPrefix() . '/' . $folder;

            if ($zipper = Zipper::make($file)) {
                $console->comment('Archive downloaded, unzipping...');
                $zipper->extractTo($diskPath);
            }

            $projectFolder = $storage->directories($folder);
            $translationFiles = $storage->files($projectFolder[0] . '/Resources/translations');

            if (!$translationFiles) {

                $console->warn('Export ended with no translations');
                return;

            } else {

                $rootPath = getcwd() . '/resources/lang';
                $resourceDisk = Storage::createLocalDriver(['root' => $rootPath]);

                collect($translationFiles)
                    ->flatMap(function ($row) {
                        $translationFile = basename($row);
                        $fileComponents = explode('.', $translationFile);
                        $language = collect($fileComponents)->get(count($fileComponents) - 2);
                        return [str_replace('_', '-', $language) => $row];
                    })
                    ->each(function ($filePath, $lang) use ($console, $storage, $resourceDisk, $languages) {

                        if (!$languages || in_array($lang, $languages)) {

                            $filename = config('loco-laravel-export.lang_filename') . '.php';

                            $content = $storage->get($filePath);

                            $resourceDisk->put($lang . '/' . $filename, $content);

                            $console->line(sprintf('Language <comment>%s</comment> saved to <info>resources/%s/%s</info>', $lang, $lang, $filename));
                        }

                    });
            }

        } catch (CommandClientException  $e) {

            if ($e->getCode() == 401) {
                $console->error('[LocoLaravelExport] Request results in 401, check your Loco Api Key.');
            } else {
                $console->error('[LocoLaravelExport] Export error.');
            }

        } catch (\Exception $e) {
            $console->error('[LocoLaravelExport] ' . $e->getMessage());
        }

    }

}