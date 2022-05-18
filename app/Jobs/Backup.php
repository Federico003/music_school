<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use DateTime;
use Log;

class Backup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = new DateTime();
        $date = $today->format('Y-m-d_His');

        $filename = "mysql-backup-{$date}.sql";
        $backup_path = storage_path() . config('backup.backup_directory');
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . $backup_path . $filename;

        $returnVar = null;
        $output = null;

        // Eseguo il backup del DB
        exec($command, $output, $returnVar);

        // Controllo che il backup sia andato a buon fine, altrimenti faccio il report e termino il processo
        if ($returnVar != 0) {
            Log::error("BackUp: Errore creazione del backup del database.");
            Log::error("command: $command");
            Log::error("output: ");
            Log::error($output);
            Log::error("return: $returnVar");
            return -1;
        }

        // Comprimo il backup del database
        $gzip_cmd = "gzip {$backup_path}{$filename}";
        exec($gzip_cmd);

        // Controllo se esiste il link all'ultimo backup
        if (file_exists("{$backup_path}/mysql-backup-last.sql.gz")) {
            // Rimuovo il link all'ultimo backup e poi lo ricreo
            $remove_sym_link_cmd = "rm {$backup_path}/mysql-backup-last.sql.gz";
            exec($remove_sym_link_cmd);
        }

        // Creo il link all'ultimo backup
        $create_sym_link_cmd = "ln -s {$backup_path}{$filename}.gz {$backup_path}/mysql-backup-last.sql.gz";
        exec($create_sym_link_cmd);

        // Controllo preliminare per la rimozione dei vecchi backup, se fallisce termina il processo
        $day_cycle = config('backup.backup_day_cycle');

        // Calcolo il giorno da cui rimuovere i backup
        $start_date = DateTime::createFromFormat('Y-m-d', $today->format('Y-m-d'))->modify("-{$day_cycle} day");

        // A questo punto proseguo con la cancellazione dei vecchi backup per non intasare la quota disco
        // Con vecchi si intende più vecchi del ciclo di vita
        if ($handle = opendir($backup_path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] != "." && $entry != "mysql-backup-last.sql.gz" && $entry != "README.md") {
                    $file_prefix = explode('_', $entry)[0];
                    $parts = explode('-', $file_prefix);
                    $fd = $parts[2] . "-" . $parts[3] . "-" . $parts[4];
                    $file_date = DateTime::createFromFormat('Y-m-d', $fd);

                    // Se la data del file è più vecchia della data di partenza
                    if ($file_date <= $start_date) {
                        $remove_old_cmd = "rm {$backup_path}/{$entry}";
                        exec($remove_old_cmd);
                    }
                }
            }

            closedir($handle);
        }

        Log::info("BackUp: Backup eseguito correttamente");
    }
}
