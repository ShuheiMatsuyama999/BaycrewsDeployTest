<item>
	<guid><?php echo $dataValue['path']; ?></guid>
	<pubDate><?php echo $dataValue['pubDate']; ?></pubDate>
	<updated><?php echo $dataValue['chgDate']; ?></updated>
	<title><?php echo $dataValue['title']; ?></title>
	<link><?php echo $dataValue['guidUrl']; ?></link>
	<description>
	<![CDATA[<?php echo $dataValue['content']; ?>]]></description>
	<enclosure url="<?php echo $dataValue['url']; ?>" type="<?php echo $dataValue['guidUrl']; ?>" />
	<sekaicamera:place><?php echo $dataValue['place']; ?></sekaicamera:place>
	<sekaicamera:address><?php echo $dataValue['address']; ?></sekaicamera:address>
	<dc:coverage><?php echo $dataValue['coverage']; ?></dc:coverage>
</item>