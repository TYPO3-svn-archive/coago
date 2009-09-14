
USE like this:

temp.myMenu = COA_GO
temp.myMenu {
    cache {
        type = afterCacheFileAjax
        hash = latest_comments
        period = 55
        refresh = 60
        onLoading = '<img src="typo3conf/ext/coago/res/ajax/1.gif" />'
        debug = 1
    }
    10 < plugin.tx_latestComments
}




CONFIGURATION:

a) "type" (is stdWrap) can be one of the following:
  1) beforeCacheDb
  2) afterCacheFile
  3) afterCacheFileAjax

b) "period" (is stdWrap) - time in second the cache expires

c) "hash" (is stdWrap) - unigue name – used in db and as filename to write cached cObject.
stdWrap here is really powerful as you can distinguish cache between pages, usergroups and user.

d) "debug" - show some information at the end of content element (time of generation). It helps to see if afterCacheFile and afterCacheFileAjax methods works as expected.

e) "refresh" (is stdWrap) - time in seconds telling ajax script how often to fetch the content without user interaction. You need to distinguish between "refresh" and "period". "Period" says when the content object expires and "refresh" only fetch the file. So if you set "period=10" and "refresh=1" then the javascript will fetch the content 10 times and after 10 times cObject will be regenerated. "Refresh" is useful if you do not know exactly what is the cache period. For example: you have "latest_comments" at the page set as "afterCacheFileAjax". You set this content object to have refresh=5 seconds. You can program you comments application to delete "typo3temp/cached_cobj/latest_comments.html" after new comment will be added. So the content object "latest_comments" will fetch the file "typo3temp/cached_cobj/latest_comments.html" every 5 seconds and if there will be no "typo3temp/cached_cobj/latest_comments.html" file (deleted by your application after commens had been added) it will regenerate the cObj "latest_comments" and fetch the new version with new comment!

f) "onLoading" (is stdWrap) - javascript code set to div contating the content elements. Allows you to inform user that the content is just fetching. Note: its part of right javascript assignment, so id this is just a text wrap it into single quote like 'content'. Example: '<img src="typo3conf/ext/coago/res/ajax/1.gif" />'

g) "fileExtension" (is stdWrap) - extension added automatically to hash value



More info in documentation at forge:
http://forge.typo3.org/projects/show/extension-coago