import React, { useState, useEffect } from "react";
import api from "../api/axios";
import "../styles/listePrestataires.css";

export default function ListePrestataires() {
  const [prestataires, setPrestataires] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetchPrestataires();
  }, []);

  const fetchPrestataires = async () => {
    try {
      setLoading(true);
      const { data } = await api.get("/api/prestataires");
      setPrestataires(data);
      setError("");
    } catch (err) {
      console.error("Erreur:", err);
      setError("Erreur lors du chargement des prestataires");
    } finally {
      setLoading(false);
    }
  };

  const handleBack = () => window.history.back();

  return (
    <div className="page-container">
      <button type="button" className="btn-back global-back" onClick={handleBack}>
        ⬅ Retour
      </button>

      <div className="liste-prestataires-container">
        <h2>Liste des Prestataires</h2>

        {error && <div className="error">{error}</div>}

        {loading ? (
          <p>Chargement...</p>
        ) : prestataires.length === 0 ? (
          <p className="no-data">Aucun prestataire enregistré.</p>
        ) : (
          <div className="table-container">
            <table className="prestataires-table">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                </tr>
              </thead>
              <tbody>
                {prestataires.map((p) => (
                  <tr key={p.id}>
                    <td>{p.nom}</td>
                    <td>{p.email}</td>
                    <td>{p.telephone}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}