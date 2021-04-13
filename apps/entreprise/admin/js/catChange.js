
function catChange(catSelect) {
	var cat = catSelect.options[catSelect.selectedIndex].value;
	var access = apps[cat].split(',');
	var accessSelect = document.getElementById('access');
	
	if (access == '') {
		accessSelect.options[0].selected = 'selected';
		for (var i = 1; i < accessSelect.length; i++) {
			accessSelect.options[i].selected = '';
		}
	} else {
		for (var i = 0 ; i < accessSelect.length ; i++) {
			if (in_array(accessSelect.options[i].value, access, false)) {
				accessSelect.options[i].selected = 'selected';
			} else {
				accessSelect.options[i].selected = '';
			}
		}
	}
}

function in_array (needle, haystack, argStrict) {
    // Checks if the given value exists in the array  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/in_array
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = argStrict;
 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }
 
    return false;
}
