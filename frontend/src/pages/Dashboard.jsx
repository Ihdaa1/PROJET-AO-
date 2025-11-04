import React from "react";
import { Link, useNavigate } from "react-router-dom";
import "../styles/dashboard.css";
import srmLogo from "../assets/logo.png";


export default function Dashboard() {
  const navigate = useNavigate();

  const logout = () => {
    localStorage.removeItem("token");
    navigate("/");
  };

  return (
    <div className="dashboard-container">
      {/* === Sidebar === */}
      <aside className="sidebar">
        <div className="sidebar-header">
          <img src={srmLogo} alt="SRM Logo" className="sidebar-logo" />
          <h2> Admin</h2>
        </div>

        <nav className="menu">
          <Link to="/dashboard" className="menu-item active">🏠 Tableau de bord</Link>
          <Link to="/add-ao" className="menu-item">📄 Ajouter un AO</Link>
          <Link to="/liste-ao" className="menu-item">📋 Liste des AO</Link>
          <Link to="/liste-prestataires" className="menu-item">👥 Liste des Prestataires</Link>
          <Link to="/add-prestataire" className="menu-item">➕ Ajouter un Prestataire</Link>
          <Link to="#" className="menu-item">📊 Statistiques</Link>
        </nav>

        <button className="logout-btn" onClick={logout}>🚪 Déconnexion</button>
      </aside>

      {/* === Contenu principal === */}
      <main className="main-content">
        <header className="main-header">
          <div className="admin-info">
            
            <div>
              <h3>Bienvenue, <span className="admin-name">Admin </span></h3>
              <p className="admin-email"></p>
            </div>
          </div>
        </header>

        <section className="dashboard-body">
          <h2>Tableau de bord</h2>
          <p>Bienvenue sur votre espace d'administration .</p>

          <div className="cards">
            <div className="card">
              <h3>📁 Appels d'Offres</h3>
              <p>Gérez les appels d'offres enregistrés dans le système.</p>
              <Link to="/add-ao" className="btn-card">Ajouter un AO</Link>
              <Link to="/liste-ao" className="btn-card" style={{marginTop: '10px'}}>Voir la liste</Link>
            </div>

            <div className="card">
              <h3>👤 Prestations</h3>
              <p>Gérez les prestataires enregistrés dans le système.</p>
              <Link to="/add-prestataire" className="btn-card">Ajouter un Prestataire</Link>
              <Link to="/liste-prestataires" className="btn-card" style={{marginTop: '10px'}}>Voir la liste</Link>
            </div>

            <div className="card">
              <h3>📈 Rapports</h3>
              <p>Consultez les statistiques des AO et prestataires.</p>
              <Link to="#" className="btn-card">Voir rapports</Link>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}
