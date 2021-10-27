/**
 * @copyright 2021 Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author 2021 Christoph Wurst <christoph@winzerhof-wurst.at>
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

import { getRequestToken } from '@nextcloud/auth'
import { loadState } from '@nextcloud/initial-state'
import { linkTo } from '@nextcloud/router'
import { translate, translatePlural } from '@nextcloud/l10n'
import Vue from 'vue'

import Booking from '../views/Appointments/Booking'

// CSP config for webpack dynamic chunk loading
// eslint-disable-next-line
__webpack_nonce__ = btoa(getRequestToken())

// Correct the root of the app for chunk loading
// OC.linkTo matches the apps folders
// OC.generateUrl ensure the index.php (or not)
// We do not want the index.php since we're loading files
// eslint-disable-next-line
__webpack_public_path__ = linkTo('calendar', 'js/')

Vue.prototype.$t = translate
Vue.prototype.$n = translatePlural

const config = loadState('calendar', 'config')
const userInfo = loadState('calendar', 'userInfo')

export default new Vue({
	el: '#appointment-booking',
	render: h => h(Booking, {
		props: {
			config,
			userInfo,
		},
	}),
})
