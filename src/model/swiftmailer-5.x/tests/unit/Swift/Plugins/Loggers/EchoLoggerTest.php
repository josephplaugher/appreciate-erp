<?php

class Swift_Plugins_Loggers_printLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddingEntryDumpsSingleLineWithoutHtml()
    {
        $logger = new Swift_Plugins_Loggers_printLogger(false);
        ob_start();
        $logger->add('>> Foo');
        $data = ob_get_clean();

        $this->assertEquals('>> Foo'.PHP_EOL, $data);
    }

    public function testAddingEntryDumpsEscapedLineWithHtml()
    {
        $logger = new Swift_Plugins_Loggers_printLogger(true);
        ob_start();
        $logger->add('>> Foo');
        $data = ob_get_clean();

        $this->assertEquals('&gt;&gt; Foo<br />'.PHP_EOL, $data);
    }
}
