<?php

require('bootstrap.php');


if (empty($_POST)) {
	$validator = null;
	$form = array();
	$errors = array();
	$h2o = new H2o('reserveren_form.html', $H2O_OPTIONS);
} else {
	$validator = new FormValidator();
	$validator->addRule('csrfmiddlewaretoken', $ruleCSRFToken);
	$validator->addRule(array('naam', 'email', 'gelegenheid', 'vereniging'), $ruleRequired);
	$validator->addRule('email', $ruleEmail);
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
		if (isset($form['confirm'])) {
			$h2o = new H2o('reserveren_email.html', $H2O_OPTIONS);
			$mail = array(
				'to' => $reservering_emailadres,
				'subject' => 'Bestelling "' . $form['gelegenheid'] . '" van ' . $form['naam'],
				'message' => wordwrap($h2o->render(
					array('formdata' => $form, 'errors' => $errors, 'validator' => $validator)),
				70),
				'headers' => 'From: no-reply@vooraadcie.nl' . "\r\n" .
                             'Reply-To: daan@voorraadcie.nl' . "\r\n" .
                             'X-Mailer: PHP/' . phpversion()
			);
			file_put_contents(
				'reserveringen/' . date('ymd') . ' ' . $form['naam'] . ' - ' . $form['gelegenheid'] . '.txt',
				json_encode($mail)
			);
			mail($mail['to'], $mail['subject'], $mail['message'], $mail['headers']);
			$h2o = new H2o('reserveren_done.html', $H2O_OPTIONS);
		} else {
			$h2o = new H2o('reserveren_confirm.html', $H2O_OPTIONS);
		}
	} else {
		$h2o = new H2o('reserveren_form.html', $H2O_OPTIONS);
	}
}


echo $h2o->render(array('formdata' => $form, 'errors' => $errors, 'validator' => $validator));

end:

require(MODULES_DIR . 'toc.php');
