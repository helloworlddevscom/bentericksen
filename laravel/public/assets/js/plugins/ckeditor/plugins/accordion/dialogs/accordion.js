CKEDITOR.dialog.add( 'accordionDialog', function ( editor ) {
    return {
        title: 'Accordion Configuration',
        minWidth: 400,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'number',
                        label: 'Number of accordions',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Must contain an integer value." )
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
            var sections = parseInt(dialog.getValueOf('tab-basic','number')); //Número de seções que serão criadas  -  Number of sections that will be created

            section = "<h3>Name of Section</h3><div><p>Section content.</p></div>"
            intern = ""
            for (i=0;i<sections;i++){
                intern = intern + section
            }

            editor.insertHtml('<div class="accordion">'+ intern +'</div>');

        }
    };
});
