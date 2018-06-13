/*
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * Service table with pop-up form.
 */
popUp = popUp || {};
popUp.default = {
    "id": "#modal-win",
    "area": ".modal-dialog .modal-content .modal-body",
    "actions": ["create", "update"]
};
(function() {
	popUp.winId = popUp.winId || function() {
        popUp.id = popUp.id || popUp.default.id;
        popUp.area = popUp.area || popUp.default.area;
        popUp.actions = popUp.actions || popUp.default.actions;
        return popUp.id + ' ' + popUp.area;
    };
    for(var i = 0; i < popUp.actions.length; i++) {
		var action = popUp.actions[i];
        $('.table td .' + action).on('click', function() {
	        $(popUp.winId()).load($(this).attr('href'));
        });
    }
}());
