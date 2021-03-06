<?php namespace App\Handlers\Commands;

use App\Commands\UserRegistrationCommand;
use App\Events\UserHasRegistered;
use App\Frendy\Repositories\Auth\RegistrationRepository;
use App\Frendy\Repositories\DbUserRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Auth\Guard;
class UserRegistrationCommandHandler extends DbUserRepository implements RegistrationRepository{

    /**
     * @var Guard
     */
    private $auth;


    /**
	 * Create the command handler.
	 *
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
        $this->auth = $auth;
    }

	/**
	 * Handle the command.
	 *
	 * @param  UserRegistrationCommand  $command
	 * @return void
	 */
	public function handle(UserRegistrationCommand $command)
	{
        $user = $this->create(['name'=>$command->name,'email'=>$command->email,'password'=>$command->password]);
        $this->auth->login($user,true);
        \Event::fire(new UserHasRegistered($user->id,$user->name,$user->email));
        return;
	}


}
