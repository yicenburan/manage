<template>
	<div>
		<h3>欢迎 {{name}}</h3>
		<a href="javascript:void(0);" @click="quit">注销登录</a>
	</div>
</template>

<script>
import { setCookie,getCookie,delCookie } from '../../assets/js/cookie.js'
	export default{
		data(){
			return{
				name: ''
			}
		},
		mounted(){
			let uname = getCookie('username')
			this.name = uname
			if(uname == ""){
				this.$router.push('/')
			}
		},
		methods:{
			quit(){
				delCookie('username')
				this.$router.push('/')
			}
		}
	}
</script>