import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../styles/Home.css";
import srmLogo from "../assets/logo.png";

export default function Home() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const rawApiBase =
    (typeof import.meta !== "undefined" && import.meta.env && import.meta.env.VITE_API_URL) ||
    (typeof process !== "undefined" && process.env && process.env.REACT_APP_API_URL) ||
    "/api"; // use Vite proxy by default to avoid CORS in dev

  const API_BASE = (() => {
    if (!rawApiBase) return "http://localhost:5000/api";
    const trimmed = String(rawApiBase).trim();
    if (/^https?:\/\//i.test(trimmed)) return trimmed.replace(/\/$/, "");
    if (trimmed.startsWith("/"))
      return `${window.location.origin}${trimmed}`.replace(/\/$/, "");
    return `http://${trimmed}`.replace(/\/$/, "");
  })();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const response = await fetch(`${API_BASE}/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();
      if (!response.ok) throw new Error(data?.message || "Email ou mot de passe incorrect !");
      localStorage.setItem("token", data?.token);
      navigate("/dashboard");
    } catch (err) {
      setError(err.message || "Une erreur est survenue. Veuillez réessayer.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="home-page">
      {/* HEADER */}
      <header className="header">
        <img src={srmLogo} alt="Logo SRM" className="header-logo" />
        <nav className="nav-links">
          <a href="#">Accueil</a>
          <a href="#about">À propos</a>
          <a href="#">Contact</a>
        </nav>
      </header>

      {/* MAIN CONTAINER */}
      <div className="home-container">
        {/* LEFT SIDE */}
        <div className="home-left">
          <img src={srmLogo} alt="Logo SRM" className="logo" />
          <h1>
            Bienvenue sur la plateforme <span className="highlight">SRM</span>
          </h1>
          <p>Gérez vos appels d’offres en toute simplicité et efficacité.</p>

          <div className="features">
            <div className="feature-item">📑 Créez et suivez vos appels d’offres</div>
            <div className="feature-item">📊 Comparez les offres reçues</div>
            <div className="feature-item">🧾 Générez des rapports détaillés</div>
            <div className="feature-item">🔒 Accès sécurisé pour les administrateurs</div>
          </div>
        </div>

        {/* RIGHT SIDE */}
        <div className="home-right">
          <form className="login-form" onSubmit={handleSubmit}>
            <h2>Connexion Administrateur</h2>
            {error && <div className="error">{error}</div>}

            <label>Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />

            <label>Mot de passe</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />

            <button type="submit" disabled={loading}>
              {loading ? "Connexion..." : "Se connecter"}
            </button>
          </form>
        </div>
      </div>

      {/* ABOUT SECTION */}
      <section id="about" className="about">
        <h3>À propos de SRM</h3>
        <p>
        La SRM-RSK, opérateur de services publics en charge de la distribution d'eau,
         d'électricité et de l'assainissement liquide dans la région de Rabat-Salé-Kénitra.
        </p>
      </section>

      {/* FOOTER */}
      <footer className="footer">
        <p>© 2025 Plateforme SRM - Gestion des Appels d'Offres</p>
      </footer>
    </div>
  );
}
