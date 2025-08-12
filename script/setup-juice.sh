
echo "Updating system..."
sudo apt update && sudo apt upgrade -y

echo "Installing Node.js and npm..."
sudo apt install -y nodejs npm git

echo " Cloning Juice Shop repo..."
git clone https://github.com/juice-shop/juice-shop.git
cd juice-shop

echo " Installing dependencies..."
npm install

echo "🚀 Starting Juice Shop on port 3000..."
npm start
