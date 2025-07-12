<?php
return [
    // Open tag used by create().
    'formStart' => '<form{{attrs}} class="p-6 rounded-lg border border-neutral-200">',
    // Close tag used by end().
    'formEnd' => '</form>',
    // General grouping container for control(). Defines input/label ordering.
    'formGroup' => '{{label}}{{input}}',
    // Used for button elements in button().
    'button' => '<div class="flex items-center align-middle justify-start gap-3"><button{{attrs}} class="py-2 px-3 bg-neutral-800 dark:bg-neutral-700 hover:bg-neutral-700 text-white dark:text-neutral-100 border border-neutral-200 dark:border-neutral-900 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">{{text}}</button></div>',
    // Used for checkboxes in checkbox() and multiCheckbox().
    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} class="w-4 h-4 text-neutral-600 bg-neutral-100 border-neutral-200 focus:ring-neutral-200 dark:focus:ring-neutral-700 dark:ring-offset-neutral-800 focus:ring-2 dark:bg-neutral-700 dark:border-neutral-600">',
    // Input group wrapper for checkboxes created via control().
    'checkboxFormGroup' => '{{label}}',
    // Wrapper container for checkboxes.
    'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
    // Error message wrapper elements.
    'error' => '<div class="error-message text-danger" id="{{id}}">{{content}}</div>',
    // Container for error items.
    'errorList' => '<ul>{{content}}</ul>',
    // Error item wrapper.
    'errorItem' => '<li>{{text}}</li>',
    // File input used by file().
    'file' => '<input type="file" name="{{name}}"{{attrs}} class="block w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-neutral-400 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white"><p class="mt-1 text-sm text-neutral-500 dark:text-neutral-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>',
    // Fieldset element used by allControls().
    'fieldset' => '<fieldset{{attrs}} class="border border-neutral-200 p-3 rounded-lg">{{content}}</fieldset>',
    // Wrapper content used to hide other content.
    'hiddenBlock' => '<div{{attrs}}>{{content}}</div>',
    // Generic input element.
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}} autocomplete="true" class="p-2 h-10 w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-neutral-400 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">',
    // Submit input element.
    'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
    // Container element used by control().
    'inputContainer' => '<div class="input {{type}}{{required}} mb-4">{{content}}<span class="help">{{help}}</span></div>',
    // Container element used by control() when a field has an error.
    'inputContainerError' => '<div class="input {{type}}{{required}} error mb-4">{{content}}{{error}}</div>',
    // Label element when inputs are not nested inside the label.
    'label' => '<label{{attrs}} class="cursor-pointer mb-2 text-sm font-medium text-neutral-900 dark:text-white">{{text}}</label>',
    // Label element used for radio and multi-checkbox inputs.
    'nestingLabel' => '{{hidden}}<label{{attrs}} class="inline-flex items-center cursor-pointer">{{input}}<span class="ms-3">{{text}}</span></label>',
    // Legends created by allControls()
    'legend' => '<legend class="p-3">{{text}}</legend>',
    // Multi-Checkbox input set title element.
    'multicheckboxTitle' => '<legend>{{text}}</legend>',
    // Multi-Checkbox wrapping container.
    'multicheckboxWrapper' => '<fieldset{{attrs}}>{{content}}</fieldset>',
    // Option element used in select pickers.
    'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
    // Option group element used in select pickers.
    'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
    // Select element,
    'select' => '<select name="{{name}}"{{attrs}} class="h-10 p-2 w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-neutral-400 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">{{content}}</select>',
    // Multi-select element,
    'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}} class="h-10 p-2 w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-neutral-400 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">{{content}}</select>',
    // Radio input element,
    'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}} class="sr-only peer"><div class="relative w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-neutral-300 dark:peer-focus:ring-neutral-800 rounded-full peer dark:bg-neutral-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[] after:absolute after:top-[2px] after:start-[2px] after:bg-white dark:after:text-neutral-800 after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-800 peer-checked:bg-neutral-800 dark:peer-checked:bg-neutral-600"></div>>',
    // Wrapping container for radio input/label,
    'radioWrapper' => '{{label}}',
    // Textarea input element,
    'textarea' => '<textarea name="{{name}}"{{attrs}} rows="10" class="p-2 w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-neutral-400 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">{{value}}</textarea>',
    // Container for submit buttons.
    'submitContainer' => '<div class="submit">{{content}}</div>',
    // Confirm javascript template for postLink()
    'confirmJs' => '{{confirm}}',
    // Templates for postLink() JS for <script> tag. (used for CSP)
    'postLinkJs'
        => 'document.getElementById("{{linkId}}").addEventListener("click", function(event) { {{content}} });',
    // selected class
    'selectedClass' => 'selected',
    // required class
    'requiredClass' => 'required',
    // Class to use instead of "display:none" style attribute for hidden elements
    'hiddenClass' => '',
];
