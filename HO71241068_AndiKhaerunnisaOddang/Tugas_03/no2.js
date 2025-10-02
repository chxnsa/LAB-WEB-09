const readline = require("readline");

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

rl.question("Masukkan harga barang: ", (harga) => {
    if (isNaN(harga)) {
        console.log("Input harga harus berupa angka!");
        rl.close();
        return;
    }

    rl.question("Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ", (jenis) => {
        harga = parseFloat(harga);
        let diskon = 0;

        switch (jenis.toLowerCase()) {
            case "elektronik":
                diskon = 0.10;
                break;
            case "pakaian":
                diskon = 0.20;
                break;
            case "makanan":
                diskon = 0.05;
                break;
            default:
                diskon = 0;
        }

        let hargaAkhir = harga - (harga * diskon);

        console.log(`Harga awal: Rp ${harga}`);
        console.log(`Diskon: ${diskon * 100}%`);
        console.log(`Harga setelah diskon: Rp ${hargaAkhir}`);

        rl.close();
    });
});
