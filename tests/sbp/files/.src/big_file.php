<?

namespace Extra\Bidule {
	
	trait Truc
	
		+ essaye $var
	
			< "(' " . ${'var'}[0]->{"ceci"} . " ')"
	
	trait Chose extends Truc
	
		+ __destruct
	
			<>essaye(array((object) {
				ceci = "Hello wordl!"
			}))
	
	interface Famille extends Yap

		* $attr = 0
	
	Fille:Mere <<< Famille, Autre, \Chose\Oups
	
		+ sayHello
	
			echo "Hello world!"
	
	\Yap\Yop:Yiip\Yup <<< \Gui\Hby

		s- lala \Yap\Uui $r, $o = (array) array(5), $i = null

			substr(**$r, 2, 4)
			< $r

}


f §
	$args = func_get_args()
	if isset($args[1]) && is_numeric($args[1])
		$translated = call_user_func_array('trans_choice', $args)
		if ! isset($args[4]) && $args[0] is $translated
			$translated = trans($args[0], $args[1], isset($args[2]) ? $args[2] : array(), isset($args[3]) ? $args[3] : 'messages', Language::altLang())
	else
		$translated = call_user_func_array('trans', $args)
		if isset($args[0]) && ! isset($args[3]) && $args[0] is $translated
			$translated = trans($args[0], isset($args[1]) ? $args[1] : array(), isset($args[2]) ? $args[2] : 'messages', Language::altLang())
	< $translated


f normalize $string, $lowerCase = true
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'
	utf8_decode(**$string)
	strtr(**$string, utf8_decode($a), $b)
	if $lowerCase
		strtolower(**$string)

	utf8_encode(**$string)
	< $string


f array_maps $maps, array $array
	if !is_array($maps)
		$maps = explode(',', $maps)

	foreach $maps as $map
		$array = array_map($map, $array)

	< $array


f scanUrl $url, $followLinks = false, $recursions = 0
	< Crawler::scanUrl($url, $followLinks, $recursions)


f ip2bin $ip = null
	< bin2hex(inet_pton(is_null($ip) ? Request::getClientIp() : $ip))


f replace $replacement, $to, $string = null
	if is_null($string)
		if !is_array($replacement)
			if !is_array($to)
				throw new InvalidArgumentException("Signatures possibles : string, string, string / array, string / array, string, string / string, array")
				< false
			< replace($to, strval($replacement))

		$string = $to
		$to = null
	if !is_null($to)
		$replacement = (array) $replacement
		$to = (array) $to
		$count = count($replacement)
		$countTo = count($to)
		if $count < $countTo
			array_slice(**$to, 0, $count)

		else if $count > $countTo
			$last = last($to)
			for $i = $countTo; $i < $count; $i++
				array_push($to, $last)

		$replacement = array_combine((array) $replacement, (array) $to)

	foreach $replacement as $from => $to
		if is_callable($to)
			$string = preg_replace_callback($from, $to, $string)

		else
			try
				// Si possible, on utilise les RegExep
				$string = preg_replace($from, $to, $string)

			catch ErrorException $e
				// Sinon on rempalcement simplement la chaîne
				$string = str_replace($from, $to, $string)

	< $string


f accents2entities $string
	< strtr($string, array(
		'é' => '&eacute;',
		'è' => '&egrave;',
		'ê' => '&ecirc;',
		'ë' => '&euml;',
		'à' => '&agrave;',
		'ä' => '&auml;',
		'ù' => '&ugrave;',
		'û' => '&ucirc;',
		'ü' => '&uuml;',
		'ô' => '&ocirc;',
		'ò' => '&ograve;',
		'ö' => '&ouml;',
		'ï' => '&iuml;',
		'ç' => '&ccedil;',
		'ñ' => '&ntild;',
		'É' => '&Eacute;',
	))


f utf8 $string
	$string = str_replace('Ã ', '&agrave; ', $string)
	if strpos($string, 'Ã') not false and strpos(utf8_decode($string), 'Ã') is false
		$string = utf8_decode(accents2entities($string))
	if !mb_check_encoding($string, 'UTF-8') and mb_check_encoding(utf8_encode($string), 'UTF-8')
		$string = utf8_encode(accents2entities($string))
	< $string


f flashAlert $textKey, $type = 'danger'
	Session::flash('alert', $textKey)
	Session::flash('alert-type', $type)
	if $type is 'danger'
		Input::flash()


f fileLastTime $file
	< max(filemtime($file), filectime($file))


f checkAssets $state = null
	static $_state = null
	if !is_null($state)
		$_state = !!$state
	elseif is_null($_state)
		$_state = Config::get('app.debug')
	< $_state


f style
	$args = func_get_args()
	if checkAssets()
		$stylusFile = CssParser::stylusFile($args[0])
		$cssFile = CssParser::cssFile($args[0], $isALib)
		$time = 0
		if file_exists($stylusFile)
			$time = DependancesCache::lastTime($stylusFile, 'fileLastTime')
			if !file_exists($cssFile) || $time > fileLastTime($cssFile)
				(new CssParser($stylusFile))->out($cssFile)
			$time -= 1363188938
		$args[0] = 'css/' . ($isALib ? 'lib/' : '') . $args[0] . '.css' . ($time ? '?' . $time : '')
	else
		$args[0] = 'css/' . (!file_exists(app_path() . '/../public/css/' . $args[0] . '.css') ? 'lib/' : '') . $args[0] . '.css'
	< call_user_func_array(array('HTML', 'style'), $args)


f script
	$args = func_get_args()
	if checkAssets()
		$coffeeFile = JsParser::coffeeFile($args[0])
		$jsFile = JsParser::jsFile($args[0], $isALib)
		$time = 0;
		if file_exists($coffeeFile)
			$time = DependancesCache::lastTime($coffeeFile, 'fileLastTime')
			if !file_exists($jsFile) || $time > fileLastTime($jsFile)
				(new JsParser($coffeeFile))->out($jsFile)
			$time -= 1363188938
		$args[0] = 'js/' . ($isALib ? 'lib/' : '') . $args[0] . '.js' . ($time ? '?' . $time : '')
	else
		$args[0] = 'js/' . (!file_exists(app_path() . '/../public/js/' . $args[0] . '.js') ? 'lib/' : '') . $args[0] . '.js'
	< call_user_func_array(array('HTML', 'script'), $args)


f image $path, $alt = null, $width = null, $height = null, $attributes = array(), $secure = null
	$time = 0
	$complete = f° $ext use &$path, &$asset, &$publicFile
		$asset .= '.' . $ext
		$publicFile .= '.' . $ext
		$path .='.' . $ext
	;
	$asset = app_path() . '/assets/images/' . $path
	$publicFile = app_path() . '/../public/img/' . $path
	if checkAssets()
		if !file_exists($asset) && !file_exists($publicFile)
			if file_exists($asset . '.png') || file_exists($publicFile . '.png')
				$complete('png')
			elseif file_exists($asset . '.jpg') || file_exists($publicFile . '.jpg')
				$complete('jpg')
			elseif file_exists($asset . '.gif') || file_exists($publicFile . '.gif')
				$complete('gif')
		if file_exists($asset)
			$time = fileLastTime($asset)
			if !file_exists($publicFile) || $time > fileLastTime($publicFile)
				copy($asset, $publicFile)
			$time -= 1363188938
	else
		if !file_exists($publicFile)
			if file_exists($publicFile . '.png')
				$complete('png')
			elseif file_exists($publicFile . '.jpg')
				$complete('jpg')
			elseif file_exists($publicFile . '.gif')
				$complete('gif')
	$image = '/img/' . $path . ($time ? '?' . $time : '')
	if ! is_null($alt) || ! is_null($width) || ! is_null($height) || $attributes !== array() || ! is_null($secure)
		if is_array($alt)
			$attributes = $alt
			$alt = null
		elseif is_array($width)
			$attributes = $width
			$width = null
		elseif is_array($height)
			$attributes = $height
			$height = null
		if ! is_null($width)
			$attributes['width'] = $width
		if ! is_null($height)
			$attributes['height'] = $height
		$image = HTML::image($image, $alt, $attributes, $secure)
	< $image


f lang
	< Lang::locale()


f starRate $id = '', $params = ''
	< (new StarPush($id))
		->images(StarPush::GRAY_STAR, StarPush::BLUE_STAR, StarPush::GREEN_STAR)
		->get($params)


f array_undot $array
	$results = array();
	foreach $array as $key => $value
		$dot = strpos($key, '.')
		if $dot === false
			$results[$key] = $value
		else
			list($first, $second) = explode('.', $key, 2)
			if ! isset($results[$first])
				$results[$first] = array()
			$results[$first][$second] = $value
	< array_map(f° $value
		< is_string($value) ? $value : array_undot($value)
	, $results)


f backUri $currentUri
	$uri = Request::server('REQUEST_URI')
	if $uri === $currentUri
		$uri = Request::server('HTTP_REFERER')
	< $uri


if !function_exists('http_negotiate_language')
	f http_negotiate_language $available_languages, &$result = null
		$http_accept_language = Request::server('HTTP_ACCEPT_LANGUAGE', '')
		preg_match_all(
			"/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
			"(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
			$http_accept_language,
			$hits,
			PREG_SET_ORDER
		)
		$bestlang = $available_languages[0]
		$bestqval = 0
		foreach $hits as $arr
			$langprefix = strtolower($arr[1])
			if !empty($arr[3])
				$langrange = strtolower($arr[3])
				$language = $langprefix . "-" . $langrange

			else
				$language = $langprefix

			$qvalue = 1.0
			if !empty($arr[5])
				$qvalue = floatval($arr[5])

			if in_array($language, $available_languages) && ($qvalue > $bestqval)
				$bestlang = $language
				$bestqval = $qvalue

			else if in_array($langprefix, $available_languages) && (($qvalue*0.9) > $bestqval)
				$bestlang = $langprefix
				$bestqval = $qvalue*0.9

		< $bestlang

Form:Illuminate\Support\Facades\Form

	s+ open $options, $second = null
		$options = is_array($options) ? $options : array('url' => $options);
		if ! is_null($second)
			array_merge(**$options, $second);
		< parent::open($options);

	s+ input $type, $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		if is_string($options)
			$options = array('class' => $options);
		if !is_null($placeholder)
			$options['placeholder'] = $placeholder;
		if !$autocomplete
			$options['autocomplete'] = 'off';
		< parent::input($type, $name, $value, $options);

	s+ text $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('text', $name, $value, $options, $placeholder, $autocomplete);

	s+ pass $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('password', $name, $value, $options, $placeholder, $autocomplete);

	s+ password $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('password', $name, $value, $options, $placeholder, $autocomplete);

	s+ checkbox $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('checkbox', $name, $value, $options, $placeholder, $autocomplete);

	s+ radio $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('radio', $name, $value, $options, $placeholder, $autocomplete);

	s+ email $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('email', $name, $value, $options, $placeholder, $autocomplete);

	s+ number $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('number', $name, $value, $options, $placeholder, $autocomplete);

	s+ color $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('color', $name, $value, $options, $placeholder, $autocomplete);

	s+ date $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('date', $name, $value, $options, $placeholder, $autocomplete);

	s+ dateTime $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('datetime', $name, $value, $options, $placeholder, $autocomplete);

	s+ localDateTime $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('datetime-local', $name, $value, $options, $placeholder, $autocomplete);

	s+ dateTimeLocal $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('datetime-local', $name, $value, $options, $placeholder, $autocomplete);

	s+ file $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('file', $name, $value, $options, $placeholder, $autocomplete);

	s+ month $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('month', $name, $value, $options, $placeholder, $autocomplete);

	s+ range $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('range', $name, $value, $options, $placeholder, $autocomplete);

	s+ search $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('search', $name, $value, $options, $placeholder, $autocomplete);

	s+ tel $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('tel', $name, $value, $options, $placeholder, $autocomplete);

	s+ time $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('time', $name, $value, $options, $placeholder, $autocomplete);

	s+ url $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('url', $name, $value, $options, $placeholder, $autocomplete);

	s+ week $name, $value = null, $options = array(), $placeholder = null, $autocomplete = true
		< static::input('week', $name, $value, $options, $placeholder, $autocomplete);


JsParser

	YES = 'yes|true|on|1';
	NO = 'no|false|off|0';

	* $coffeeFile;

	+ __construct $coffeeFile
		>coffeeFile = $coffeeFile;

	+ out $jsFile
		< file_put_contents(
			$jsFile,
			>parse(>coffeeFile)
		);

	s+ resolveRequire $coffeeFile, $firstFile = null
		if is_null($firstFile)
			$firstFile = $coffeeFile
		< preg_replace_callback(
			'#\/\/-\s*require\s*\(?\s*([\'"])(.*(?<!\\\\)(?:\\\\{2})*)\\1(?:[ \t]*,[ \t]*(' . :YES . '|' . :NO . '))?[ \t]*\)?[ \t]*(?=[\n\r]|$)#i',
			f° $match use $coffeeFile, $firstFile
				$file = stripslashes($match[2]);
				$file = preg_match('#^(http|https|ftp|sftp|ftps):\/\/#', $file) ?
					$file :
					static::findFile($file);
				$isCoffee = empty($match[3]) ?
					ends_with($file, '.coffee') :
					in_array(strtolower($match[3]), explode('|', :YES));
				DependancesCache::add($firstFile, $file);
				$file = static::resolveRequire($file, $firstFile)
				if ! $isCoffee
					$file = "`$file`";
				< $file
			,
			file_get_contents($coffeeFile)
		)

	+ parse $coffeeFile
		DependancesCache::flush($coffeeFile);
		$code = CoffeeScript\Compiler::compile(
			static::resolveRequire($coffeeFile),
			array(
				'filename' => $coffeeFile,
				'bare' => true
			)
		);
		if ! Config::get('app.debug')
			$code = preg_replace('#;(?:\\r\\n|\\r|\\n)\\h*#', ';', $code);
			$code = preg_replace('#(?:\\r\\n|\\r|\\n)\\h*#', ' ', $code);
		< $code;

	s* findFile $file
		if file_exists($file)
			< $file;
		$coffeeFile = static::coffeeFile($file);
		if file_exists($coffeeFile)
			< $coffeeFile;
		< static::jsFile($file);

	s+ coffeeFile $file, &$isALib = null
		$files = array(
			app_path() . '/assets/scripts/' . $file . '.coffee',
			app_path() . '/../public/js/lib/' . $file . '.coffee',
		);
		foreach $files as $iFile
			if file_exists($iFile)
				$isALib = str_contains($iFile, 'lib/');
				< $iFile;
		< array_get($files, 0);

	s+ jsFile $file, &$isALib = null
		$jsDir = app_path() . '/../public/js/';
		foreach array($jsDir, $jsDir . 'lib/') as $dir
			foreach array('coffee', 'js') as $ext
				if file_exists($dir . $file . '.' . $ext)
					$isALib = ends_with($dir, 'lib/');
					< $dir . $file . '.js';
		< app_path() . '/../public/js/' . $file . '.js';


HomeController:BaseController

	+ searchBar
		<>view('home')
	
	+ searchResultForm $page = 1, $q = null, $resultsPerPage = null
		<>searchResult($page, $q, $resultsPerPage, true)

	+ searchResult $page = 1, $q = null, $resultsPerPage = null, $form = false
		$q = is_null($q) ? Request::get('q', $page) : urldecode($q)
		$data = CrawledContent::getSearchResult($q)
				->paginatedData($page, $resultsPerPage, array(
				'q' => $q,
				'pageUrl' => '/%d/'.urlencode($q).'{keepResultsPerPage}',
				'resultsPerPageUrl' => '/'.$page.'/'.urlencode($q).'/%d'
			))
		if $form
			LogSearch::log($q, $data['nbResults'])
		<>view('result', $data)

	+ goOut $search_query, $crawledContent
		$id = $crawledContent->id

		LogOutgoingLink::create(array(
			'search_query' => $search_query,
			'crawled_content_id' => $id
		))
		$count = Cache::get('crawled_content_id:'.$id.'_log_outgoing_link_count');
		if $count
			$count++
		else
			$count = LogOutgoingLink::where('crawled_content_id', $id)->count()
		Cache::put('crawled_content_id:'.$id.'_log_outgoing_link_count', $count, CrawledContent::REMEMBER)

		< Redirect::to($crawledContent->url)

	+ delete $crawledContent
		if ! User::current()->isModerator()
			Session::flash('back-url', '/delete/' . $crawledContent->id)
			< Redirect::to('/user/login')

		<>view('delete', array(
			'result' => $crawledContent
		))

	+ deleteConfirm $crawledContent
		if ! User::current()->isModerator()
			< Redirect::to('/user/login')
		$crawledContent->delete()
		flashAlert('global.delete-succeed', 'success')

		< Redirect::to('/')

	+ addUrl
		Session::regenerateToken()
		if ! User::current()->isContributor()
			< Redirect::to('/user/login')
		$url = Input::get('url')
		$state = scanUrl($url)
		<>view('home', array(
			'url' => $url,
			'state' => $state
		))

	+ mostPopular $page, $resultsPerPage = null
		<>view('result',
			CrawledContent::popular()
				->select(
					'crawled_contents.id',
					'url', 'title', 'content', 'language',
					DB::raw('COUNT(log_outgoing_links.id) AS count')
				)
				->orderBy('count', 'desc')
				->paginatedData($page, $resultsPerPage, array(
					'q' => '',
					'pageUrl' => '/most-popular/%d{keepResultsPerPage}',
					'resultsPerPageUrl' => '/most-popular/'.$page.'/%d'
				))
			)

	+ history $page, $resultsPerPage = null
		$data = LogSearch::mine()
			->paginatedData($page, $resultsPerPage, array(
				'q' => '',
				'pageUrl' => '/history/%d{keepResultsPerPage}',
				'resultsPerPageUrl' => '/history/'.$page.'/%d'
			))
		$data['resultsGroups'] = $data['results']->groupBy(f° $element
			<$element->created_at->uRecentDate
		)
		<>view('history', $data)

/**
 * Contenu récupéré par le crawler
 */
CrawledContent:Model

	* $collection = 'crawled_content';
	* $softDelete = true;
	* $fillable = array('url', 'title', 'content', 'language');

	SAME_LANGUAGE = 8;
	SAME_PRIMARY_LANGUAGE = 4;

	/**
	 * Retourne les résultats d'une recherche
	 *
	 * @param string $query : l'expression à rechercher
	 *
	 * @return CrawledContent $resultsContainigQuery
	 */
	s+ getSearchResult $query
		$calledClass = get_called_class();
		<self::search($query, $values) // $values contient les mots contenus dans la chaîne $query sous forme d'array
			->select(
				'crawled_contents.id',
				'url', 'title', 'content', 'language', 'deleted_at',
				DB::raw('COUNT(log_outgoing_links.id) AS count'),
				DB::raw(
					self::caseWhen(DB::raw('language'), array(
						Lang::locale() => :SAME_LANGUAGE
					), 0) . ' + ' .
					self::caseWhen(self::substr(DB::raw('language'), 1, 2), array(
						substr(Lang::locale(), 0, 2) => :SAME_PRIMARY_LANGUAGE
					), 0) . ' +
					COUNT(DISTINCT key_words.id) * ' . :KEY_WORD_SCORE . ' + ' .
					self::findAndCount(DB::raw('content'), $query).' * ' . :COMPLETE_QUERY_SCORE . ' + '.
					self::findAndCount(DB::raw('content'), $values).' * ' . :ONE_WORD_SCORE . '
					AS score
				')
			)
			->leftJoin('log_outgoing_links', 'log_outgoing_links.crawled_content_id', '=', 'crawled_contents.id')
			->leftJoin('crawled_content_key_word', 'crawled_content_key_word.crawled_content_id', '=', 'crawled_contents.id')
			->leftJoin('key_words', f° $join use $calledClass, $values
				$join->on('crawled_content_key_word.key_word_id', '=', 'key_words.id')
					->on('key_words.word', 'in', DB::raw('(' . implode(', ', array_maps(array('normalize', 'strtolower', array($calledClass, 'quote')), $values)) . ')'));
			)
			->groupBy('crawled_contents.id')
			->orderBy('score', 'desc');

	/**
	 * Retourne les résultats sur lesquels quelqu'un a déjà cliqué au moins une fois (lié à 1 ou plusieurs LogOutgoingLink)
	 *
	 * @return CrawledContent $popularResults
	 */
	s+ popular
		<static::leftJoin('log_outgoing_links', 'log_outgoing_links.crawled_content_id', '=', 'crawled_contents.id')
			->whereNotNull('log_outgoing_links.id')
			->groupBy('crawled_contents.id');

	+ keyWords
		<>belongsToMany('KeyWord');

	+ scan
		scanUrl(>attributes['url']);

	+ getOutgoingLinkAttribute
		<'/out/'. (empty(self::$lastQuerySearch) ? '-' : self::$lastQuerySearch) . '/' . $this->id;

	+ getUrlAndLanguageAttribute
		<>url . (empty($this->language) ? '' : '(' . $this->language . ')');

	+ link $label, array $attributes = array()
		<HTML::link($this->outgoingLink, $label, $attributes);

	+ getCountAttribute
		<Cache::get('crawled_content_id:' . $this->id . '_log_outgoing_link_count', array_get($this->attributes, 'count', 0));

	+ resume $length = 800
		$content = trim(Cache::get('CrawledContent-' . $this->id . '-content', array_get($this->attributes, 'content', '')));
		if strlen($content) > $length
			substr(**$content, 0, $length);
			substr(**$content, 0, strrpos($content, ' ')) . '...';
		$closeStrongTag = substr_count($content, '<strong>') - substr_count($content, '</strong>');
		$content .= str_repeat('</strong>', $closeStrongTag);
		<utf8($content);

	+ getContentAttribute
		<>resume();

	+ getTitleAttribute
		<utf8(Cache::get('CrawledContent-' . $this->id . '-title', array_get($this->attributes, 'title', '')));


?>