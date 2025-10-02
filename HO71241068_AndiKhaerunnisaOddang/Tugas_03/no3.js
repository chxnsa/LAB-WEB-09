const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const days = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];

rl.question("Masukkan hari: ", (hari) => {
    let startDayIndex = days.indexOf(hari.toLowerCase());

    if (!startDayIndex === -1) {
        console.log("Input hari tidak valid!");
        rl.close();
        return;
    }

    rl.question("Masukkan jumlah hari ke depan: ", (jumlah) => {
        if (isNaN(jumlah)) {
            console.log("Input jumlah hari harus berupa angka!");
            rl.close();
            return;
        }

        jumlah = parseInt(jumlah);
        let resultDay = days[(startDayIndex + jumlah) % 7];

        console.log(`${jumlah} hari setelah ${hari} adalah ${resultDay.charAt(0).toUpperCase() + resultDay.slice(1)}`);
        rl.close();
    });
});
