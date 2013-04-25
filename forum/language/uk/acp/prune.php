<?php
/**
*
* acp_prune [Ukrainian]
*
* @package language
* @version $Id: prune.php,v 1.14 2007/10/04 15:07:24 acydburn Exp $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// User pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_USERS_EXPLAIN'	=> 'Тут ви можете видаляти або деактивувати користувачів вашого форуму. Облікові записи можна відфільтрувати різними способами - за кількістю повідомлень, за часом останнього відвідування і т.д. Критерії можна суміщати з метою зменшення кількості облікових записів, до яких буде застосовано очищення. Наприклад, ви можете видалити користувачів, які не відвідували конференцію після 01.01.2002, та мають менше десяти повідомлень. Також ви можете повністю пропустити вибір критеріїв, ввівши список користувачів (кожного на окремому рядку) в текстовому полі. Будьте обережні з цим інструментом - видалених користувачів відновити неможливо!',

	'DEACTIVATE_DELETE'			=> 'Деактивувати або видалити',
	'DEACTIVATE_DELETE_EXPLAIN'	=> 'Оберіть дію над користувачами. Зауважте, що видалених користувачів неможливо буде відновити!',
	'DELETE_USERS'				=> 'Видалити',
	'DELETE_USER_POSTS'			=> 'Видалити повідомлення користувачів',
	'DELETE_USER_POSTS_EXPLAIN' => 'Видаляє повідомлення користувачів, написані користувачами, які видаляються, не застосовується, якщо ви обрали деактивацію користувачів.',

	'JOINED_EXPLAIN'			=> 'Введіть дату в форматі <kbd>РРРР-ММ-ДД</kbd>.',

	'LAST_ACTIVE_EXPLAIN'		=> 'Введіть дату в форматі <kbd>РРРР-ММ-ДД</kbd>. Введіть <kbd>0000-00-00</kbd> для очищення користувачів, які ніколи не заходили на форум, в такому випадку умови <em>до</em> та <em>після</em> буде проігноровано.',

	'PRUNE_USERS_LIST'				=> 'Очищення користувачів',
	'PRUNE_USERS_LIST_DELETE'		=> 'За обраними критеріями для очищення користувачів буде видалено наступні облікові записи.',
	'PRUNE_USERS_LIST_DEACTIVATE'	=> 'За обраними критеріями для очищення користувачів буде деактивовано наступні облікові записи.',

	'SELECT_USERS_EXPLAIN'		=> 'Введіть тут конкретні імена користувачів, їх буде використано замість вищевказаних критеріїв. Зауважте, що засновники не можуть бути видаленими або вимкненими.',

	'USER_DEACTIVATE_SUCCESS'	=> 'Обраних користувачів було успішно деактивовано.',
	'USER_DELETE_SUCCESS'		=> 'Обраних користувачів було успішно видалено.',
	'USER_PRUNE_FAILURE'		=> 'Не знайдено користувачів, які відповідають обраним критеріям.',

	'WRONG_ACTIVE_JOINED_DATE'	=> 'Введена дата не є коректною, формат дати повинен бути <kbd>РРРР-ММ-ДД</kbd>.',
));

// Forum Pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_FORUMS_EXPLAIN'	=> 'Будуть видалені усі теми, в які не писали і не проглядали встановлену кількість днів. Якщо ви не введете кількість днів, то усі теми буде видалено. За замовчуванням, не будуть видалені теми з опитуваннями, а також прикріплені теми та оголошення.',

	'FORUM_PRUNE'		=> 'Очищення форумів',

	'NO_PRUNE'			=> 'Очищення форумів не проведено.',

	'SELECTED_FORUM'	=> 'Обраний форум',
	'SELECTED_FORUMS'	=> 'Обрані форуми',

	'POSTS_PRUNED'					=> 'Видалено повідомлень',
	'PRUNE_ANNOUNCEMENTS'			=> 'Видаляти оголошення',
	'PRUNE_FINISHED_POLLS'			=> 'Видаляти закриті опитування',
	'PRUNE_FINISHED_POLLS_EXPLAIN'	=> 'Видаляє теми з опитуваннями, які закінчились.',
	'PRUNE_FORUM_CONFIRM'			=> 'Ви впевнені, що хочете очистити форуми з заданими налаштуваннями? Видалені повідомлення та теми не підлягають відновленню.',
	'PRUNE_NOT_POSTED'				=> 'Днів з останнього повідомлення',
	'PRUNE_NOT_VIEWED'				=> 'Днів з останнього перегляду',
	'PRUNE_OLD_POLLS'				=> 'Видаляти старі голосування',
	'PRUNE_OLD_POLLS_EXPLAIN'		=> 'Видаляти теми з голосуваннями, в яких не голосували протягом вказаної кількості днів.',
	'PRUNE_STICKY'					=> 'Видаляти прикріплені теми',
	'PRUNE_SUCCESS'					=> 'Очищення форумів пройшло успішно.',

	'TOPICS_PRUNED'		=> 'Видалено тем',
));

?>