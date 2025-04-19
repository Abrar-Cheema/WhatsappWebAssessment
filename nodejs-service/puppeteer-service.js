// puppeteer-service.js
const puppeteer = require('puppeteer');
const fs = require('fs');
const axios = require('axios');
const FormData = require('form-data');

(async () => {
  // const browser = await puppeteer.launch({ headless: true });
  const browser = await puppeteer.launch({
    executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', // Update if different
    headless: true
  });


  const page = await browser.newPage();
  await page.goto('https://web.whatsapp.com');


  // await page.waitForSelector('canvas[aria-label="Scan me!"]', { timeout: 60000 });
  await page.waitForSelector('canvas[aria-label^="Scan this QR code"]', { timeout: 60000 });

  console.log('browser');

  // const qrData = await page.$eval('canvas[aria-label="Scan me!"]', canvas => canvas.toDataURL());
  const qrData = await page.$eval('canvas[aria-label^="Scan this QR code"]', canvas => canvas.toDataURL());

  const base64Data = qrData.replace(/^data:image\/png;base64,/, "");
  const filename = 'qr_code.png';
  fs.writeFileSync(filename, base64Data, 'base64');

  const form = new FormData();
  form.append('qr_code', fs.createReadStream(filename));

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/upload-qr', form, {
      headers: form.getHeaders()
    });
    console.log('QR Code URL:', response.data.qr_url);
  } catch (error) {
    console.error('Upload failed:', error.message);
  }
  console.log('request sent');
  page.on('framenavigated', async () => {
    const url = page.url();
    if (url.includes('https://web.whatsapp.com')) {
      const isLoggedIn = await page.evaluate(() => !document.querySelector('canvas[aria-label="Scan me!"]'));
      if (isLoggedIn) {
        console.log('User logged in successfully');
        await browser.close();
      }
    }
  });
})();
