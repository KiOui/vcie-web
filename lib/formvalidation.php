<?php

/**
 * Form Validation
 *
 * @package     Form Validation
 * @author      Daan Sprenkels
 * @link        
 * @version     0.0.1
 * 
 **/

abstract class Rule {
	protected $_errorMessage;
	public abstract function check($v);
	public function errorMessage($field, $value) {
		$result = $this->_errorMessage;
		$result = preg_replace("/{{\s*field\s*}}/i", $field, $result);
		$result = preg_replace("/{{\s*value\s*}}/i", $value, $result);
		return $result;
	}
}

class RegexRule extends Rule {
	private	 $pattern;
	
	public function __construct($pattern, $errorMessage="onjuist") {
		$this->pattern = $pattern;
		$this->_errorMessage = $errorMessage;
	}
	
	public function check($subject) {
		return (boolean) preg_match($this->pattern, $subject);
	}
}

class MinRule extends Rule {
	protected $minValue;
	
	public function __construct($minValue, $errorMessage='onjuist') {
		$this->minValue = $minValue;
		$this->_errorMessage = $errorMessage;
	}
	
	public function check($a) {
		return (boolean) strlen($a) == 0 || $a >= $this->minValue;
	}
}

class CSRFTokenRule extends Rule {
	public function check($csrf_token) {
		if ( ! array_key_exists('csrftoken', $_COOKIE)) {
			trigger_error("No CSRF token cookie provided", E_USER_ERROR);
		}
		if ($csrf_token !== $_COOKIE['csrftoken']) {
			trigger_error("CSRF token not valid", E_USER_ERROR);
		}
		return true;
	}
}

class InvRule extends Rule {
	protected $_rule;
	
	public function __construct($rule, $errorMessage='onjuist') {
		$this->_rule = $rule;
		$this->_errorMessage = $errorMessage;
	}
	
	public function check($a) {
		return ! (boolean) $this->_rule->check($a);
	}
}

class FormValidator {
	
	protected $_rules = array();
	
	public function addRule($fields, $rule) {
		if (is_string($fields)) {
			$fields = array($fields);
		}
		foreach ($fields as $field) {
			$this->_rules[] = array('field' => $field, 'rule' => $rule);
		}
	}
	
	public function addRules($fields, $rules) {
		if (is_string($fields)) {
			$fields = array($fields);
		}
		foreach ($rules as $rule) {
			$this->addRule($fields, $rule);
		}
	}
	
	public function getErrors($data=null) {
		if (is_null($data)) {
			$data = $_POST;
		}
		$result = array();
		foreach ($this->_rules as $row) {
			$field = $row['field'];
			$rule = $row['rule'];
			if (! array_key_exists($field, $data)) {
				$data[$field] = '';
			}
			if (! $rule->check($data[$field])) {
				if (! array_key_exists($field, $result)) {
					$result[$field] = array();
				}
				$result[$field][] = $rule->errorMessage($field, $data[$field]);
			}
		}
		return $result;
	}

}

$ruleNumberInteger = new RegexRule('/^$|^[\+\-]?[0-9]+$/', "moet een getal zijn");
$ruleNumberFloat = new RegexRule('/^$|^[\+\-]?[0-9\.]+$/', "moet een getal zijn");
$ruleAlpha = new RegexRule('/^$|^[a-zA-Z0-9_\-\. ]+$/', "moet alfanumeriek");
$ruleEmail = new RegexRule('/(^$)|^([a-zA-Z0-9_\+\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/', "geen geldig e-mail adres");
$ruleMinzero = new MinRule(0, "waarde mag niet negatief zijn.");
$ruleRequired = new InvRule(new RegexRule('/^$/'), 'mag niet leeg zijn');
$ruleCSRFToken = new CSRFTokenRule();
