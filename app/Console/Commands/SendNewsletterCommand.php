<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewsletterNotification;
use Illuminate\Console\Command;

class SendNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @param array arreglo de emails opcional al cual se enviaran  las notis
     */
    protected $signature = 'send:newsletter 
                            {emails?*} : Correos Electronicos a los cuales enviar directamente
                            {--s|schedule : Si debe der ejecutado directamente o no}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = $this->arguments( 'emails' );
        $schedule = $this->option('schedule');

        $builder = User::query();

        if( isset($emails['emails']) ){
            $builder->whereIn( 'email', $emails['emails'] );
        }

        $count = $builder->count();

        if( $count ){
            $this->info("Se enviaran {$count} correos");

            if( $this->confirm('¿Estas de acuerdo?') || $schedule ){
                $this->output->progressStart( $count );

                User::query()
                    ->whereNotNull( 'email_verified_at' )
                    ->each( function (User $user){
                            $user->notify(new NewsletterNotification());
                            $this->output->progressAdvance();
                        } );

                $this->output->progressFinish();
                return $this->info( "Se enviaron {$count} correos" );
            }
        }else{
            $this->info('No se envió ningun correo');
        }

    }
}
