//function to make an input field into an autocompleter with multiselection
//json_url : points to a file which provides the possible values in a json array
//selector : the field(s) which need the functionality 
function makeAutoCompleter(selector, json_url,minlength){
//    $(selector).prop('disabled', true);
    $.ajax({
            url: json_url,
            context: document.body,
            dataType: "json"
    }).done(function(data) {
        var availableTags = data;
        $( selector ).autocomplete({
            source: availableTags
        });
        function split( val ) {
                return val.split( /,\s*/ );
        }
        function extractLast( term ) {
          return split( term ).pop();
        }
        $( selector ) // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
          if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
          }
        })
        .autocomplete({
          minLength: 2,
          source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( $.ui.autocomplete.filter(
                  availableTags, extractLast( request.term ) ) );
          },
          focus: function() {
                // prevent value inserted on focus
                return false;
          },
          select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
          }
        });
//        $(selector).prop('disabled', false);
    });
}