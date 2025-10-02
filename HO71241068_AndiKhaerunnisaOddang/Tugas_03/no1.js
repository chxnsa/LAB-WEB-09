function countEvenNumbers(start, end) {
    if (typeof start !== "number" || typeof end !== "number") {
        return "Input harus berupa angka!";
    }

    if (start > end) {
        return "Nilai 'start' tidak boleh lebih besar dari 'end'.";
    }

    let genap = [];
    for (let i = start; i <= end; i++) {
        if (i % 2 == 0) {
            genap.push(i);
        }
    }

    return genap.length + " [" + genap.join(", ") + "]";
}

console.log(countEvenNumbers(1, 10));  