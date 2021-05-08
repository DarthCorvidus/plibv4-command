<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class CommandTest extends TestCase {
	function testConstruct() {
		$command = new Command("rsync");
		$this->assertInstanceOf(Command::class, $command);
	}
	
	function testGetCommand() {
		$command = new Command("rsync");
		$this->assertEquals("rsync", $command->buildCommand());
	}
	
	function testGetComplexCommand() {
		$command = new Command("rsync");
		$command->addParameter("/home/user/");
		$command->addParameter("/backup/");
		$command->addParameter("-avz");
		$command->addParameter("-n");
		$command->addParameter("--progress");
		$command->addParameter("--exclude-file", "/home/user/exclude.txt");
		$compare[] = "rsync";
		$compare[] = escapeshellarg("/home/user/");
		$compare[] = escapeshellarg("/backup/");
		$compare[] = escapeshellarg("-avz");
		$compare[] = escapeshellarg("-n");
		$compare[] = escapeshellarg("--progress");
		$compare[] = escapeshellarg("--exclude-file")."=".escapeshellarg("/home/user/exclude.txt");
		$this->assertEquals(implode(" ", $compare), $command->buildCommand());
	}
	
	function testExecuteSilent() {
		$command = new Command("ls");
		$command->addParameter(__DIR__);
		$this->expectOutputString("");
		$command->exec();
	}
	
	function testExecShowCommand() {
		$command = new Command("ls");
		$command->showCommand();
		$command->addParameter(__DIR__."/CommandTest.php");
		$this->expectOutputString("ls ". escapeshellarg(__DIR__."/CommandTest.php").PHP_EOL);
		$command->exec();
	}

	function testExecShowOutput() {
		$command = new Command("ls");
		$command->showOutput();
		$command->addParameter(__DIR__."/CommandTest.php");
		$this->expectOutputString(__DIR__."/CommandTest.php\n");
		$command->exec();
	}

	function testExecShowOutputPrefixed() {
		$command = new Command("ls");
		$command->showOutput();
		$command->setPrefix("test:");
		$command->addParameter(__DIR__."/CommandTest.php");
		$this->expectOutputString("test:".__DIR__."/CommandTest.php\n");
		$command->exec();
	}

	function testExecShowOutputAll() {
		$command = new Command("ls");
		$command->showOutput();
		$command->showCommand();
		$command->setPrefix("test:");
		$command->addParameter(__DIR__."/CommandTest.php");
		$this->expectOutputString("ls ". escapeshellarg(__DIR__."/CommandTest.php").PHP_EOL."test:".__DIR__."/CommandTest.php\n");
		$command->exec();
	}

}
