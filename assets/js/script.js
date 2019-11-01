/**
 * Disable selection
 */
window.onload = function() {
	disableSelection( document.body );
};

function disableSelection( target ) {
	if ( typeof target.onselectstart !== 'undefined' ) {
		target.onselectstart = function() {
			return false;
		};
	} else if ( typeof target.style.MozUserSelect !== 'undefined' ) {
		target.style.MozUserSelect = 'none';
	} else {
		target.onmousedown = function() {
			return false;
		};
	}
	target.style.cursor = 'default';
}

/**
 * Disable Drag
 */
document.ondragstart = function() {
	return false;
};

/**
 * Disable context menu
 *
 * @param {Object} e - event
 */
document.oncontextmenu = function( e ) {
	const t = e || window.event;
	const elm = t.target || t.srcElement;

	if ( elm.nodeName === 'A' && elm.type === 'text' || elm.type === 'password' ) {
		return true;
	}

	if ( wpccpMessage ) {
		// eslint-disable-next-line
		alert( wpccpMessage );
	}

	return false;
};

/**
 * Disable Ctrl/meta + key and other actions
 */
window.addEventListener( 'keyup', wpccpDisableCtrlActions, false );
window.addEventListener( 'keydown', wpccpDisableCtrlActions, false );

function wpccpDisableCtrlActions( e ) {
	// Display msg if performing copy
	const wpccpIsCopy = ( e.ctrlKey || e.metaKey ) && ( e.key === 'c' );
	if ( wpccpMessage && wpccpIsCopy ) {
		// eslint-disable-next-line
		alert( wpccpMessage );
	}

	// Disable key combinations
	const wpccpComboKeys = [ 'a', 'b', 'c', 'f', 'p', 's', 'u', 'x', 'shift', 'i', 'j' ];

	if ( ! wpccpPaste ) {
		wpccpComboKeys.push( 'v' );
	}

	const wpccpIncludesKey = wpccpComboKeys.includes( e.key );
	if ( ( e.ctrlKey || e.metaKey ) && wpccpIncludesKey ) {
		e.preventDefault();
	}
}

window.addEventListener( 'keyup', wpccpDisableKeys, false );
window.addEventListener( 'keydown', wpccpDisableKeys, false );

function wpccpDisableKeys( e ) {
	/**
	 *  KeyCode Info:
	 * ---------------------------------------------------
	 *  91: Windows Key / Left ⌘ / Chromebook Search key
	 *  93: Windows Key / Left ⌘ / Chromebook Search key
	 * 224: left or right ⌘ key (firefox)
	 */
	const wpccpKeys = [ 'Control', 'Alt', 'F12' ];
	const wpccpKeyCodes = [ 91, 93, 224 ];

	if ( wpccpKeys.includes( e.key ) || wpccpKeyCodes.includes( e.keyCode ) ) {
		e.preventDefault();
	}
}

/**
 * Disable Print Screen
 */
// TODO: Test as its not reliable
window.addEventListener( 'keyup', wpccpDisablePrintScreen, false );

function wpccpDisablePrintScreen( e ) {
	/**
	 *  KeyCode Info:
	 * ---------------------------------------------------
	 *  44: Print Screen
	 */
	const isPrintScreen = e.keyCode === 44;
	if ( isPrintScreen ) {
		window.location.replace( 'http://google.com/' );
	}
}
