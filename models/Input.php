<?php namespace Models;


class Input {

	protected $print = true;

	protected $html = null;

	protected $label = null;

	protected $displayLabel = true;

	protected $displayGroup = true;

	protected $displayInput = true;

	protected $classes = array('form-control');

	protected $acf_type = null;

	protected $acf_id = null;

	public function __construct($args) {

		if ( is_string($args) ) {
			$this->name = strtolower($args);
			$this->placeholder = $args;
			$this->label = $args;
			$this->id = strtolower($args);
			$this->key = strtolower($args);
		}

		if ( is_array($args) ) {
			foreach ( $args as $key => $value ) {
				switch ( $key ) {
					case 'key':
						$this->$key = $value;
						$this->id = $value;
						$this->name = $value;
					break;

					case 'type':
						// dont let the ACF override the actual input
						$this->acf_type = $value;
					break;

					case 'id':
						// dont let the ACF override the actual input
						$this->acf_id = $value;
					break;

					default:
						$this->$key = $value;
					break;
				}
			}
		}
	}

	public function label($label)
	{
		if ( is_null($label) ) {
			$this->displayLabel = false;
		} else {
			$this->displayLabel = true;
			$this->label = $label;
		}

		// chaining methods anyway
		return $this;
	}

	public function id($id)
	{
		$this->id = $id;
		return $this;
	}

	public function group($group)
	{
		$this->group = (bool)$group;
		return $this;
	}

	public function __call($function, $args) 
	{
		if ( !is_array($args) ) {
			$this->$function = $args;
		} else {
			switch ( count($args) ) {
				case 1:
					$this->$function = $args[0];
				break;

				default:
					// some logic here
				break;
			} 
		}

		return $this;

	}

	public function addClass($class)
	{
		$this->classes[] = $class;

		return $this;
	}

	public function __destruct() 
	{
		if ( $this->print )
			echo $this->getHtml();
	}

	public function getLabelStartHtml() 
	{
		$html = null;

		$html .= '<label for="'.$this->id.'">';
			$html .= $this->label;
		$html .= '</label>';

		return $html;
	}

	public function getLabelEndHtml() 
	{
		$html = null;

		return $html;
	}

	public function getBeforeInputHtml() 
	{
		$html = null;

		return $html;
	}

	public function getAfterInputHtml() 
	{
		$html = null;

		return $html;
	}

	public function conditionalLogicHtmlStart()
	{
		if ( !$this->conditional_logic['status'] )
			return null;	// no conditional logic applied to this field

		$html = null;

		$html .= '
			<script type="text/javascript">
				$(function(){
			';

			foreach ( $this->conditional_logic['rules'] as $rule ) {
				$html .= '
				function checkConditionOnField'.$this->id.'() {
					var $el = $("#'.$this->id.'").parents(".hide-wrapper");
					if ( $("#'.$rule['field'].'").val() '.$rule['operator'].' "'.$rule['value'].'" ) {
						$el.removeClass("hidden");
					} else {
						$el.addClass("hidden");
					}
				}
				checkConditionOnField'.$this->id.'();

				$("#'.$rule['field'].'").change(function(){
					checkConditionOnField'.$this->id.'();
				});
				';
			}

		$html .= '
				});
			</script>
		';

		$html .= '<div class="hidden hide-wrapper">';

		return $html;
	}

	public function conditionalLogicHtmlEnd()
	{
		if ( !$this->conditional_logic['status'] )
			return null;	// no conditional logic applied to this field

		$html = null;

		$html .= '</div>';

		return $html;
	}

	public function getHtml() 
	{
		$html = null;

		$html .= $this->conditionalLogicHtmlStart();

		if ( $this->displayGroup ) {
			$html .= '<div class="form-group">';
		}

		if ( $this->displayLabel ) {
			$html .= $this->getLabelStartHtml();	// primarily for radios and checkboxes, labels are separated into start and end
		}

		if ( $this->displayInput ) {
			$html .= $this->getBeforeInputHtml();
		}

		if ( $this->displayInput ) {
			$html .= $this->getInputHtml();
		}

		if ( $this->displayInput ) {
			$html .= $this->getBeforeInputHtml();
		}

		if ( $this->displayLabel ) {
			$html .= $this->getLabelEndHtml();
		}

		if ( $this->displayGroup ) {
			$html .= '</div>';
		}

		$html .= $this->conditionalLogicHtmlEnd();

		return $html;
	}

	public function getInputHtml() 
	{
		$html = null;

		$html .= '<input type="'.$this->type.'" 
			name="'.$this->name.'" 
			class="'.implode(' ', $this->classes).'"
			id = "'.$this->id.'"
			value = "'.$this->value.'" 
			'.(!empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : '' ).' 
			'.($this->required ? 'required' : '').'
			
			>';

		return $html;
	}
}
