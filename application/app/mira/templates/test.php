<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>

apihelp = {
	init: function(){
		console.log("inited");
	},

	get: function(url){
		this.url = url
	},

	show: function(){
		
	},

	extract: function(data, status){
	}
}

class apihelper {
	
	constructor(){
		//alert("created")
		this.url = ["yo", "hey"]
		this.data = "placeholder"
	}

	get(url){
		this.url = url
	}

	setData(response) {
		this.data = response
	}

	getData() {
		this.values = this.data[0].id
		document.write(this.data[0].username)
	}

	show(){
	}
}
var oReq = new XMLHttpRequest();
var resp = "check scope";

var api = new apihelper()
var help = apihelp
oReq.addEventListener("load", function(){
	let number = JSON.parse(this.responseText)
	//console.log(this.responseText)
	api.setData(number)
	console.log("API WRAPPER: " + api.getData())
	help.values = number
});
oReq.open("GET", "localhost/api/users/");
oReq.send();
console.log(oReq)




</script>












<!--

var xmlHttp = createXmlHttpRequestObject()
function createXmlHttpRequestObject(){
	var xmlHttp

	if (window.ActiveXObject) {
		try {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
		} catch (e) {
			xmlHttp = false
		}
	} else {
		try {
			xmlHttp = new XMLHttpRequest()
		} catch (e) {
			xmlHttp = false
		}
	}

	if (!xmlHttp) {
		alert("COULD NOT START HTTP")
	} else {
		return xmlHttp
	}
}
-->