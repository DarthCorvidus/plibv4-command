<?php
class Command {
	private $command;
	private $params = array();
	private $showCommand = FALSE;
	private $showOutput = FALSE;
	private $prefix;
	/**
	 * Construct
	 * @param string $command The command as such, like rsync or /usr/bin/rsync
	 */
	function __construct(string $command) {
		$this->command = $command;
	}
	
	/**
	 * Set Prefix
	 * 
	 * A prefix will pe prepended to a line. Just imagine you're running several
	 * long jobs with a lot of output: a prefix may help the user to see what
	 * job is running right now. Note that the prefix is printed out as is, so
	 * it's up to you to add your own separator.
	 * @param string $prefix
	 */
	function setPrefix(string $prefix) {
		$this->prefix = $prefix;
	}
	
	/**
	 * Show Command
	 * 
	 * Shows the final command with parameters before calling it.
	 */
	function showCommand() {
		$this->showCommand = TRUE;
	}
	
	/**
	 * Show Output
	 * 
	 * Shows the output of a command, with prefix, if set.
	 */
	function showOutput() {
		$this->showOutput = TRUE;
	}
	
	/**
	 * Add Parameter
	 * 
	 * Add a parameter as key => value pair. You can omit a value if you want to
	 * use flags or positional values. Please note that you have to deliver any
	 * dashes or similar characters by yourself, and that parameters and values
	 * will be subjected to escapeshellarg() for safety reasons.
	 * Key & value will be combined using =.
	 * @param type $key
	 * @param type $value
	 * @return type
	 */
	function addParameter($key, $value=NULL) {
		if($value === NULL) {
			$this->params[] = escapeshellarg($key);
		return;
		}
		$this->params[] = escapeshellarg($key)."=".escapeshellarg($value);
	}
	
	/**
	 * Build Command
	 * 
	 * Returns a command ready to execute.
	 * @return string
	 */
	function buildCommand(): string {
		$command = array();
		$command[] = $this->command;
		$command = array_merge($command, $this->params);
	return implode(" ", $command);
	}
	
	function exec() {
		$command = $this->buildCommand();
		if($this->showCommand) {
			echo $this->buildCommand().PHP_EOL;
		}
		$handle = popen($command, "r");
		while($line = fgets($handle)) {
			if($this->showOutput && $this->prefix === NULL) {
				echo $line;
			}
			if($this->showOutput && $this->prefix !== NULL) {
				echo $this->prefix.$line;
			}

		}
		pclose($handle);
	}
}
