<?php

class Pcntl_Signal {

	public static $signal = NULL;

	public static function install()
	{
		declare(ticks = 1);

		pcntl_signal(SIGTERM, array('Signal', 'handle'));
		pcntl_signal(SIGHUP, array('Signal', 'handle'));
		pcntl_signal(SIGUSR1, array('Signal', 'handle'));
		pcntl_signal(SIGUSR2, array('Signal', 'handle'));
		
	}

	public static function handle($signal)
	{
		Signal::$signal = $signal;

		Event::run('pcntl.signal');
	}
	
}