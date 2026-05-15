const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const cors = require('cors');

const app = express();
const port = 3000;

// Middleware
app.use(express.json());
app.use(cors());

// Inisialisasi WhatsApp Client
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

// Event saat QR Code muncul
client.on('qr', (qr) => {
    console.log('QR RECEIVED, PLEASE SCAN:');
    qrcode.generate(qr, { small: true });
});

// Event saat client sudah siap
client.on('ready', () => {
    console.log('WhatsApp Client is ready!');
});

// Endpoint untuk kirim pesan
app.post('/send-message', async (req, res) => {
    const { number, message } = req.body;

    if (!number || !message) {
        return res.status(400).json({
            status: false,
            message: 'Number and message are required!'
        });
    }

    try {
        // Format nomor: hilangkan karakter non-digit dan pastikan berakhiran @c.us
        let formattedNumber = number.replace(/\D/g, '');
        if (!formattedNumber.endsWith('@c.us')) {
            formattedNumber += '@c.us';
        }

        // Kirim pesan
        await client.sendMessage(formattedNumber, message);

        res.json({
            status: true,
            message: `Message sent successfully to ${number}`
        });
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({
            status: false,
            message: 'Failed to send message',
            error: error.message
        });
    }
});

// Endpoint untuk kirim media (PDF/Gambar)
app.post('/send-media', async (req, res) => {
    const { number, message, filename, file } = req.body;

    if (!number || !file) {
        return res.status(400).json({
            status: false,
            message: 'Number and file (base64) are required!'
        });
    }

    try {
        let formattedNumber = number.replace(/\D/g, '');
        if (!formattedNumber.endsWith('@c.us')) {
            formattedNumber += '@c.us';
        }

        const media = new MessageMedia('application/pdf', file, filename || 'Receipt.pdf');

        await client.sendMessage(formattedNumber, media, { caption: message });

        res.json({
            status: true,
            message: `Media sent successfully to ${number}`
        });
    } catch (error) {
        console.error('Error sending media:', error);
        res.status(500).json({
            status: false,
            message: 'Failed to send media',
            error: error.message
        });
    }
});

// Jalankan Express Server
app.listen(port, () => {
    console.log(`WA Gateway Server running at http://localhost:${port}`);
});

// Inisialisasi Client
client.initialize();
