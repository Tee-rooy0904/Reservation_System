const INFOBIP_API_KEY = 'kq834n.api.infobip.com';
const INFOBIP_BASE_URL = 'https://kq834n.api.infobip.com';

async function sendSMS(phoneNumber, message) {
    try {
        // Format phone number for Philippines (remove leading 0 and add +63)
        const formattedPhone = '+63' + phoneNumber.replace(/^0/, '');

        const response = await fetch(`${INFOBIP_BASE_URL}/sms/2/text/advanced`, {
            method: 'POST',
            headers: {
                'Authorization': `App ${INFOBIP_API_KEY}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                messages: [{
                    destinations: [{ to: formattedPhone }],
                    text: message
                }]
            })
        });

        const result = await response.json();
        console.log('SMS sent successfully:', result);
        return {
            success: true,
            message: 'SMS sent successfully',
            data: result
        };
    } catch (error) {
        console.error('Error sending SMS:', error);
        return {
            success: false,
            message: 'Failed to send SMS',
            error: error.message
        };
    }
}

// Example usage:
// sendSMS('0912345678', 'Your reservation has been approved!');

// Export the function to use in other files
module.exports = { sendSMS };