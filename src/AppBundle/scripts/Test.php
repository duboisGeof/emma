<?php
namespace AppBundle\script;
use Org_Heigl\Ghostscript\Ghostscript;

$gs = new Ghostscript ();

		// Set the output-device
		$gs->setDevice('jpeg')
		// Set the input file
		   ->setInputFile('/applications/Geoffrey/emma/src/AppBundle/pdf/test.pdf')
		// Set the output file that will be created in the same directory as the input
		   ->setOutputFile('/applications/Geoffrey/emma/src/AppBundle/pdf/test2.pdf')
		// Set the resolution to 96 pixel per inch
		   ->setResolution(96)
		// Set Text-antialiasing to the highest level
		   ->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH)
		// Set the jpeg-quality to 100 (This is device-dependent!)
		   ->getDevice()->setQuality(100);
		if (true === $gs->render()) {
			echo 'success';
		} else {
			echo 'some error occured';
		}