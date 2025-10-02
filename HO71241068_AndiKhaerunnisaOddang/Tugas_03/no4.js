const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

let randomNumber = Math.floor(Math.random() * 100) + 1;
let attempts = 0;

function guessNumber() {
    rl.question("Masukkan salah satu dari angka 1 sampai 100: ", (input) => {
        let guess = parseInt(input);

        if (isNaN(guess)) {
            console.log("Input harus berupa angka!");
            guessNumber();
            return;
        }

        attempts++;

        if (guess < randomNumber) {
            console.log("Terlalu rendah! Coba lagi.");
            guessNumber();
        } else if (guess > randomNumber) {
            console.log("Terlalu tinggi! Coba lagi.");
            guessNumber();
        } else {
            console.log(`Selamat! kamu berhasil menebak angka ${randomNumber} dengan benar.`);
            console.log(`Sebanyak ${attempts}x percobaan.`);
            rl.close();
        }
    });
}

guessNumber();
