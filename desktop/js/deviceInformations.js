/* This file is part of Plugin openzwave for jeedom.
 *
 * Plugin openzwave for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin openzwave for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin openzwave for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
$('#bt_refreshValues').on('click',function(){
    display_values();
});

/*
 * Load device informations values to the modal body
 */
function display_values(){
    jeedom.panasonicVIERA.informations({
        id : $('.eqLogicAttr[data-l1key=id]').value(),
        error: function (error) {
            $('#div_valuesAlert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#ul_tabs').empty();
            $('#div_tabscontent').empty();

            var first = true;
            Object.keys(data).forEach(function(key) {
                var cls = first === true ? 'active' : '';
                $('#ul_tabs').append('<li class="' + cls +'"><a href="#'+ key +'tab" role="tab" data-toggle="tab"><i class="fa fa-info"></i> ' + key + '</a></li>');
                $('#div_tabscontent').append('<div role="tabpanel" class="' + cls +' tab-pane" id="' + key + 'tab"><ul></ul></div>');
                $('#' + key + 'tab ul').empty().append(traverseJson(data[key]));
                first = false;
            });
        }
    });
}

/*
 * Parse the JSON object and build a simple HTML tree
 *
 * @param [object] the JSON object to parse
 */
function traverseJson(node) {
    var html = '';
    for (key in node) {
        if (!!node[key] && typeof(node[key])=="object") {
            html += ['<li><b>', key, '</b>'].join('');
            html += '<ul>';
            html += traverseJson(node[key]);
            html += '</li></ul>';
        } else {
            html += '<li>';
            html += ['<b>', key, '&nbsp&nbsp&nbsp&nbsp =&gt; &nbsp&nbsp&nbsp&nbsp', node[key], '</b>'].join('');
            html += '</li>';
        }
    }
    return html;
}

display_values();
