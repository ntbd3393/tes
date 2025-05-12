const express = require("express");
const app = express();
const port = process.env.PORT || 8080;
const { JWTStrategy } = require("@sap/xssec");
const xsenv = require("@sap/xsenv");
const passport = require("passport");
const bcrypt = require("bcrypt");
const bodyParser = require("body-parser");
const mongoose = require("mongoose");

// Koneksi ke MongoDB
mongoose.connect("mongodb://localhost:27017/mysaasdb", {
  useNewUrlParser: true,
  useUnifiedTopology: true,
});

// Skema Admin
const adminSchema = new mongoose.Schema({
  username: String,
  password: String,
  subdomain: { type: String, unique: true },
});

const Admin = mongoose.model("Admin", adminSchema);

passport.use(new JWTStrategy(xsenv.getServices({ uaa: { tag: "xsuaa" } }).uaa));

app.use(bodyParser.json());
app.use(passport.initialize());
app.use(passport.authenticate("JWT", { session: false }));

// Endpoint Registrasi
app.post("/register", async (req, res) => {
  const { username, password, subdomain } = req.body;

  const existingAdmin = await Admin.findOne({ subdomain });
  if (existingAdmin) {
    return res.status(400).send("Subdomain sudah digunakan.");
  }

  const hashedPassword = await bcrypt.hash(password, 10);
  const newAdmin = new Admin({
    username,
    password: hashedPassword,
    subdomain,
  });

  await newAdmin.save();
  res.status(201).send("Registrasi berhasil.");
});

// Middleware untuk memeriksa akses admin berdasarkan subdomain
async function checkAdminAccess(req, res, next) {
  const subdomain = req.params.subdomain;
  const admin = await Admin.findOne({ subdomain });

  if (!admin) {
    return res.status(404).send("Admin tidak ditemukan.");
  }

  if (req.user.subdomain !== subdomain) {
    return res.status(403).send("Dilarang");
  }

  next();
}

// Contoh rute untuk mendapatkan daftar karyawan untuk admin tertentu
app.get("/getemplist/:subdomain", checkAdminAccess, (req, res) => {
  // Logika untuk mengambil daftar karyawan untuk admin tertentu
  res.send("Daftar karyawan untuk admin " + req.params.subdomain);
});

app.listen(port, () => console.log(`Mendengarkan di port ${port}`));