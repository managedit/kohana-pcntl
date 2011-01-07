<?php

class Pcntl_Thread {
	protected $_pid = NULL;

	protected function __construct()
	{
		// Use the factory method...
	}

	public static function factory($callback, $arguments = NULL)
	{
		$thread = new Thread;

		$thread->run($callback, $arguments);

		return $thread;
	}

	protected function run($callback, $arguments = NULL)
	{
		$pid = pcntl_fork();

		if ($pid == -1)
		{
			throw new Exception('Could not fork');
		}
		else if ($pid)
		{
			// Inside the parent
			$this->_pid = $pid;
		}
		else
		{
			// Inside the child
			Signal::install();

			// Go go go..
			if (is_array($arguments))
			{
				call_user_func_array($callback, $arguments);
			}
			else
			{
				call_user_func($callback);
			}

			// Done? Lets exit cleanly.. or not.
//			exit(0);
		}
	}

	public function is_running()
	{
		$pid = pcntl_waitpid($this->_pid, $status, WNOHANG);

        return ($pid === 0);
	}

	public function kill($signal = SIGKILL, $block = FALSE)
	{
		if ($this->is_running())
		{
			posix_kill($this->_pid, $block);

			if ($block)
			{
				pcntl_waitpid($this->_pid);
			}
		}
	}
}
