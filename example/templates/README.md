You need to create a section for the download overview (type: channel, uri: download/{slug}, template: _entry)
and one for the file download itself (type: single, uri: _rawdownload_, template: _download)

The download *must* have a asset field called _filename_ to work. 
