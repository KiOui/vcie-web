<?php

require('bootstrap.php');


if (empty($_POST)) {
	$validator = null;
	$form = array();
	$errors = array();
	$template = $twig->loadTemplate('reserveren_form.html');
} else {
	$validator = new FormValidator();
	$validator->addRule('csrfmiddlewaretoken', $ruleCSRFToken);
	$validator->addRule(array('naam', 'email', 'dag', 'maand', 'jaar', 'gelegenheid', 'vereniging', 'beginTijd', 'eindTijd'), $ruleRequired);
	$validator->addRule('email', $ruleEmail);
	$validator->addRule(array('dag', 'maand'), new MinRule(1, 'Een dag of maand onder de 1 maakt helemaal geen sense.'));
	$validator->addRule('dag', new MaxRule(31, 'Dag groter dan 31?'));
	$validator->addRule('maand', new MaxRule(12, 'Maand groter dan 12?'));
	$validator->addRule(array('beginTijd', 'eindTijd'), new RegexRule('/^$|^\d\d?[:.]\d?\d$/i', 'sorry, ik snap deze tijd niet, probeer iets als &lsquo;13:37&rsquo;'));
	$validator->addRule('kantine', new RegexRule('/^$|^(zuid|noord)kantine$/i', "geen geldige kantine :P"));
	$validator->addRules(array(
		'krattenBier', 'rodeWijn', 'witteWijnZoet', 'witteWijnDroog',
		'colaRegular', 'colaLight', 'fanta', 'sprite', 'spaRood',
		'appelsap', 'sinaasappelsap', 'cassis', 'iceTeaSparkling',
		'iceTeaNoBubbles', 'rioFiestaMix', 'AHCocktailNoten',
		'stapelglazen', 'wijnglazen', 'statafels'
	), array($ruleNumberInteger, $ruleMinzero));
	$form = $_POST;
	$errors = $validator->getErrors();
	if (empty($errors)) {
		$form['datetime'] = mktime(0, 0, 0, $form['maand'], $form['dag'], $form['jaar']);
		if (isset($form['confirm'])) {
			$template = $twig->loadTemplate('reserveren_email.html');
			$mail = array(
				'to' => $reservering_emailadres,
				'subject' => 'Bestelling "' . $form['gelegenheid'] . '" van ' . $form['naam'],
				'message' => wordwrap($template->render(
					array('formdata' => $form, 'errors' => $errors, 'validator' => $validator)),
				70),
				'headers' => 'From: no-reply@vooraadcie.nl' . "\r\n" .
                             'Reply-To: ' . $form['email'] . "\r\n" .
                             'X-Mailer: PHP/' . phpversion()
			);
			file_put_contents(
				'reserveringen/' . date('ymd') . ' ' . $form['naam'] . ' - ' . $form['gelegenheid'] . '.txt',
				json_encode($mail)
			);
			mail($mail['to'], $mail['subject'], $mail['message'], $mail['headers']);
			$template = $twig->loadTemplate('reserveren_done.html');
		} else {
			$template = $twig->loadTemplate('reserveren_confirm.html');
		}
	} else {
		$template = $twig->loadTemplate('reserveren_form.html');
	}
}


echo $template->render(array('formdata' => $form, 'errors' => $errors, 'validator' => $validator));

end:

require(LIB_DIR . 'toc.php');
