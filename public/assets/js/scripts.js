const showHiddenPassword = () => {
	let inputPassword = document.querySelector("#input-password");
	if (inputPassword.type === "password") {
		inputPassword.type = "text";
	} else {
		inputPassword.type = "password";
	}
}

const generateRandomString = (limit) => {
	const characters = "0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ";
	let result = "";
	const charactersLength = characters.length;
	for (let i = 0; i < limit; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
};

const generatePassword = () => {
	const password = document.querySelector("#input-password");
	const current_year = new Date().getFullYear();
	password.value = generateRandomString(12);
};