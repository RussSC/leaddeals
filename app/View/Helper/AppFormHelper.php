<?php
/**
 * Extends the basic FormHelper
 *
 **/

App::uses('BoostCakeFormHelper', 'BoostCake.View/Helper');
App::uses('Hash', 'Utility');

class AppFormHelper extends BoostCakeFormHelper {

/**
 * Extends Form create function to allow site-wise input defaults
 *
 * @param string $model The current model
 * @param array $options Additional form options
 * @return string The opening form tag
 **/
	public function create($model = null, $options = array()) {
		$options = Hash::merge(array(
			'inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => false,
				'class' => 'form-control',
			)
		), $options);
		return parent::create($model, $options);
	}

	public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);

		if (!empty($options['multiple']) && empty($options['class'])) {
			$options['class'] = $options['multiple'];
		}

		if (!empty($options['type'])) {
			$type = $options['type'];
		} else {
			$checkOptions = $this->_parseOptions($options);
			$type = $checkOptions['type'];
		}

		if ($type == 'radio') {
			$options = $this->addClass($options, 'radio');
		} else if ($type == 'checkbox') {
			$options = $this->addClass($options, 'checkbox');
		} else if ($type == 'phone') {
			$options = $this->append('<i class="fa fa-phone"></i>', $options);
		} else if ($type == 'email') {
			$options = $this->append('<i class="fa fa-at"></i>', $options);
		}

		if (!empty($options['help'])) {
			if (empty($options['afterInput'])) {
				$options['afterInput'] = '';
			}
			$options['afterInput'] .= '<span class="help-block">' . $options['help'] . '</span>';
			unset($options['help']);
		}
		
		return parent::input($fieldName, $options);
	}

	public function submit($caption = null, $options = []) {
		$options = $this->addClass($options, 'btn btn-primary btn-lg');
		return parent::submit($caption, $options);
	}

	public function inputCopy($value, $options = []) {
		$options['value'] = $value;
		$name = 'input-copy-do-not-user';
		$options['div'] = 'form-group form-group-copy';
		$options['readonly'] = true
;		$options['beforeInput'] = '<div class="input-group">';
		$options['afterInput'] = '<span class="input-group-btn">';
		$options['afterInput'] .= '<button type="button" class="btn btn-default">Copy</button>';
		$options['afterInput'] .= '</span>';
		$options['afterInput'] .= '</div>';

		return $this->input($name, $options);
	}

	/*
	public function inputs($fields = null, $blacklist = null, $options = []) {
		if (!empty($fields['legend'])) {
			$legend = $fields['legend'];
		}
		$fields['fieldset'] = false;
		$fields['legend'] = false;
		$out = parent::inputs($fields, $blacklist, $options);

		if (!empty($legend)) {
			$out = $this->Html->div('panel-body', $out);
			$out = $this->Html->div('panel panel-default', $this->Html->div('panel-heading', $legend) . $out);
		}
		return $out;
	}
	*/

	public function prepend($content, $options = array()) {
		$options['beforeInput'] = '<div class="input-group"><span class="input-group-addon">' . $content . '</span>';
		$options['afterInput'] = '</div>';
		return $options;
	}

	public function append($content, $options = array()) {
		$options['beforeInput'] = '<div class="input-group">';
		$options['afterInput'] = '<span class="input-group-addon">' . $content . '</span></div>';
		return $options;
	}

}