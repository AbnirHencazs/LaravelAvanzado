<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendVerifyEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:verifyemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía una notificación a todos los usuarios que no hayan verificado su cuenta y se hayan registrado hace más de una semana';

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
        $count = User::query()->whereNull( 'email_verified_at' )->count();
        if( $count ){
            $this->output->progressStart( $count );

            User::query()
                ->whereNull( 'email_verified_at' )
                ->whereDate( 'created_at', '<=', Carbon::now()->subDays(7)->format('Y-m-d') )
                ->each( function(User $user){
                    $user->sendEmailVerificationNotification();
                    $this->output->progressAdvance();
                } );

            $this->output->progressFinish();
            return $this->info( "Se enviaron {$count} correos" );
        }else{
            $this->info( "No se han enviado correos" );
        }
    }
}
