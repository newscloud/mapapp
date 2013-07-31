<?php

/**
 * PlacesAutoComplete class file.
 *
 * @author Petra Barus <petra.barus@gmail.com>
 * @link http://github.com/petrabarus
 * 
 * @copyright (c) 2012, Petra Barus
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights 
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is 
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * PlacesAutoComplete wraps Google Places Autocomplete API.
 *
 * PlacesAutoComplete encapsulates the {@link https://developers.google.com/maps/documentation/javascript/places#places_autocomplete
 * Google Places Autocomplete.
 * 
 * To use this widget, you first add this library to extensions directory. And then
 * you can insert the following code in a view:
 * <pre> 
 * $this->widget('ext.gplacesautocomplete.GPlacesAutoComplete', array(
 *    'name' => 'city',
 *    'options' => array(
 *       'types' => array(
 *          '(cities)'
 *       ),
 *       'componentRestrictions' => array(
 *          'country' => 'us',
 *        )
 *    )
 * ));
 * </pre>
 * 
 * To configure the options please see the {@link https://developers.google.com/maps/documentation/javascript/places#adding_autocomplete
 * specification}.
 * 
 * @author Petra Barus <petra.barus@gmail.com>
 * @package ext.placesautocomplete
 * @version 0.1
 */
class GPlacesAutoComplete extends \CInputWidget {

        /**
         * @var string script executed after the autocomplete is declared.
         */
        public $afterScript = '';

        /**
         * @var string script executed before the autocomplete is declared.
         */
        public $beforeScript = '';

        /**
         * @var string variable name to store the autocomplete object. Will use
         * the widget ID if unset.
         */
        public $objectName = NULL;

        /**
         * @var array Autocomplete options. Refer to {@link https://developers.google.com/maps/documentation/javascript/places#adding_autocomplete}
         */
        public $options = array();

        /**
         * @var boolean whether the Google API libray is registered from this widget.
         */
        public $registerLibrary = true;

        /**
         * @var boolean whether to use sensor.
         */
        public $sensor = false;

        /**
         * Runs the widget.
         */
        public function run() {
                list($name, $id) = $this->resolveNameID();
                if (isset($id))
                        $this->id = $id;

                if (isset($this->htmlOptions['id']))
                        $id = $this->htmlOptions['id'];
                else
                        $this->htmlOptions['id'] = $id;

                if (isset($this->htmlOptions['name']))
                        $name = $this->htmlOptions['name'];

                if (!isset($this->objectName))
                        $this->objectName = $this->id;

                if ($this->hasModel())
                        echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
                else
                        echo CHtml::textField($name, $this->value, $this->htmlOptions);

                $this->registerScript();
        }

        /**
         * Register the scripts.
         * 
         * This method will register the library needed and the scripts to create
         * the autocomplete.
         */
        public function registerScript() {
                /* @var $cs CClientScript */
                $cs = Yii::app()->clientScript;
                if ($this->registerLibrary)                        $cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?' . http_build_query(array(
                                        'libraries' => 'places',
                                        'sensor' => $this->sensor ? 'true' : 'false',
                                )));
                $options = CJSON::encode($this->options);
                $cs->registerScript(__CLASS__ . '#' . $this->id, <<<JS
(function(){
        var input = document.getElementById('{$this->id}');
        var options = {$options};
        {$this->beforeScript}
        {$this->objectName} = new google.maps.places.Autocomplete(input, options);
        {$this->afterScript}
})();
JS
                );
        }

}