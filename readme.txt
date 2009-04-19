
USE like this:

temp.a1 = COA_GO
temp.a1.cache.type = afterCache_file
temp.a1.cache.period = 30 
temp.a1.cache.hash = footer


where
a) "type" can be one of the following:
   1) beforeCache_db
   2) afterCache_file
   3) afterCache_file_ajax
   
b) "period" - time in second the cache expires

c) "hash" - unigue name – used in db and as filename to write cached cObject.