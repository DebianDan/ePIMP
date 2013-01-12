function retrieve_get() {
	var get = [], hash;
	var q = document.URL.split('?')[1];
	if(q != undefined){
		q = q.split('&');
		for(var i = 0; i < q.length; i++){
			hash = q[i].split('=');
			get.push(hash[1]);
			get[hash[0]] = hash[1];
		}
	}
	return get;
}