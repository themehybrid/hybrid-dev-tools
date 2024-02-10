<?php

namespace Hybrid\DevTools\Composer;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use Hybrid\DevTools\Composer\Util\Arr;
use Hybrid\DevTools\Composer\Util\Filesystem as UtilFilesystem;

class Actions {

    /**
     * @see https://github.com/mailpoet/mailpoet/blob/trunk/mailpoet/tools/install.php#L9C1-L13C3
     * @var array<string>
     */
    protected static array $tools = [
        'composer-normalize.phar' => [
            'url' => 'https://github.com/ergebnis/composer-normalize/releases/download/2.41.1/composer-normalize.phar',
            'ver' => '2.41.1',
        ],
        'php-scoper.phar'         => [
            'url' => 'https://github.com/humbug/php-scoper/releases/download/0.18.10/php-scoper.phar',
            'ver' => '0.18.10',
        ],
        'pint.phar'               => [
            'url' => 'https://github.com/laravel/pint/releases/download/v1.13.7/pint.phar',
            'ver' => 'v1.13.7',
        ],
        'psalm.phar'              => [
            'url' => 'https://github.com/vimeo/psalm/releases/download/5.18.0/psalm.phar',
            'ver' => '5.18.0',
        ],
        'parallel-lint.phar'      => [
            'url' => 'https://github.com/php-parallel-lint/PHP-Parallel-Lint/releases/download/v1.3.2/parallel-lint.phar',
            'ver' => 'v1.3.2',
        ],
    ];

    protected static string $tools_bin_path;

    protected static string $tools_configs_path;

    public static function downloadTools( Event $event ) {
        $io = $event->getIO();

        if ( ! $event->isDevMode() ) {
            $io->write( 'Not downloading dependencies, due to not being in dev mode.' );
            return;
        }

        $composer           = $event->getComposer();
        $composer_root_path = dirname( $composer->getConfig()->get( 'bin-dir' ), 2 );
        $extras             = $composer->getPackage()->getExtra();
        $filesystem         = new UtilFilesystem( new Filesystem() );
        $bin_path           = 'bin-dev/tools';
        $configs_path       = 'bin-dev/tools/configs';
        $additional_tools   = [];
        $skip_tools         = false;
        $skip_config        = false;
        $overwrite_configs  = false;

        if ( Arr::exists( $extras, 'hybrid-dev-tools' ) ) {
            $tools_config = $extras['hybrid-dev-tools'];

            $skip_tools        = Arr::get( $tools_config, 'skip-tools', false );
            $skip_config       = Arr::get( $tools_config, 'skip-configs', false );
            $overwrite_configs = Arr::get( $tools_config, 'overwrite-configs', false );
            $bin_path          = $filesystem->filesystem->normalizePath( Arr::get( $tools_config, 'bin-path', $bin_path ) );
            $configs_path      = $filesystem->filesystem->normalizePath( Arr::get( $tools_config, 'configs-path', $configs_path ) );
            $additional_tools  = Arr::get( $tools_config, 'tools', [] );
        }

        self::$tools_bin_path     = $composer_root_path . '/' . $bin_path;
        self::$tools_configs_path = $composer_root_path . '/' . $configs_path;
        self::$tools              = array_merge( self::$tools, $additional_tools );

        if ( ! $skip_tools ) {
            foreach ( self::$tools as $name => $data ) {
                fwrite( STDERR, "Processing '$name'\n" );

                $ver_file        = self::$tools_bin_path . '/.' . $filesystem->name( $name ) . '-ver';
                $ver_file_exists = file_exists( $ver_file );
                $download_file   = ! $ver_file_exists || file_get_contents( $ver_file ) !== $data['ver'];
                $force_download  = $ver_file_exists && $download_file;

                if ( $download_file || $force_download ) {
                    $filesystem->writeContent( $data['ver'], $ver_file );
                    self::downloadFile( $data['url'], self::$tools_bin_path . "/$name", $force_download );
                } else {
                    fwrite( STDERR, sprintf( "- skipped (%s version already exists).\n", $data['ver'] ) );
                }
            }
        }

        if ( ! $skip_config ) {
            $configs_source = dirname( __DIR__, 2 ) . '/bin/configs';
            $configs_target = self::$tools_configs_path;

            $files = $filesystem->glob( "$configs_source/*" );

            foreach ( $files as $file ) {
                $file_name   = basename( $file );
                $target_file = "$configs_target/$file_name";

                if ( $overwrite_configs || ! file_exists( $target_file ) ) {
                    fwrite( STDERR, "Copying '$file' to '$configs_target'.\n" );
                    $filesystem->copyFile( $file, $target_file );
                } else {
                    fwrite( STDERR, "Skipped copying '$file' (already exists).\n" );
                }
            }

        }
    }

    public static function downloadFile( $url, $file_path, $force_download ) {
        fwrite( STDERR, sprintf( "%s.\n", $force_download ? '- updating' : '- downloading' ) );

        if ( file_exists( $file_path ) && ! $force_download ) {
            fwrite( STDERR, " skipped (already exists).\n" );
            return;
        }

        $resource = fopen( $url, 'r' );
        if ( $resource === false ) {
            throw new \RuntimeException( "Could not connect to '$url'" );
        }

        $filesystem = new UtilFilesystem( new Filesystem() );

        fwrite( STDERR, "-- to path: '$file_path'.\n" );

        if ( $force_download ) {
            $filesystem->unlinkOrRemove( $file_path );
        }

        if ( $filesystem->writeContent( file_get_contents( $url ), $file_path ) ) {
            fwrite( STDERR, sprintf( "--- successfully %s\n", $force_download ? 'updated' : 'downloaded' ) );
        } else {
            fwrite( STDERR, sprintf( "--- failed to %s\n", $force_download ? 'update' : 'download' ) );
        }
    }

}
