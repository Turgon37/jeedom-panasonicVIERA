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

function display_values(){
    jeedom.panasonicVIERA.informations({
        id : $('.eqLogicAttr[data-l1key=id]').value(),
        error: function (error) {
            $('#div_valuesAlert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var tbody = '';
            console.log(data);
            Object.keys(data).forEach(function(key) {
                tbody += '<tr>';
                tbody += ['<td>', key, '</td>'].join('');
                tbody += ['<td>', data[key]['raw'], '</td>'].join('');
                tbody += ['<td>', data[key]['integer'], '</td>'].join('');
                tbody += ['<td>', data[key]['float'], '</td>'].join('');
                tbody += ['<td>', data[key]['word'], '</td>'].join('');
                tbody += ['<td>', data[key]['unit'], '</td>'].join('');
                tbody += '</tr>';
            });
            $('#table_values tbody').empty().append(tbody)
        }
    });
}

display_values();
