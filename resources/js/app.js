require('./bootstrap');

require('./requests/likes/storeLike');
require('./requests/likes/destroyLike');
require('./requests/messages/fetchMessage');
require('./requests/messages/storeMessage');
require('./requests/messages/updateMessage');
require('./requests/messages/destroyMessage');

require('./states/messages');

require('alpinejs');
