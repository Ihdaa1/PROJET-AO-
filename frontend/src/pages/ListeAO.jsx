import React, { useState, useEffect } from "react";
import api from "../api/axios";
import "../styles/listeAO.css";

export default function ListeAO() {
  const [appelOffres, setAppelOffres] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetchAppelOffres();
  }, []);

  const fetchAppelOffres = async () => {
    try {
      setLoading(true);
      setError("");
      const { data } = await api.get("/api/appel-offres");
      
      // Debug : vérifier les données reçues
      console.log("Données reçues:", data);
      
      // Vérifier si data est un tableau
      if (Array.isArray(data)) {
        setAppelOffres(data);
      } else {
        console.error("Les données ne sont pas un tableau:", data);
        setAppelOffres([]);
      }
    } catch (err) {
      console.error("Erreur:", err);
      setError(err.response?.data?.error || "Erreur lors du chargement des appels d'offres");
      setAppelOffres([]);
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

      <div className="liste-ao-container">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
          <h2>Liste des Appels d'Offres</h2>
          <button 
            type="button" 
            onClick={fetchAppelOffres}
            style={{
              padding: '10px 20px',
              backgroundColor: '#00ad9e',
              color: 'white',
              border: 'none',
              borderRadius: '6px',
              cursor: 'pointer',
              fontSize: '14px'
            }}
            disabled={loading}
          >
            🔄 Rafraîchir
          </button>
        </div>

        {error && <div className="error">{error}</div>}

        {loading ? (
          <p style={{ textAlign: 'center', padding: '40px' }}>Chargement...</p>
        ) : appelOffres.length === 0 ? (
          <p className="no-data">Aucun appel d'offre enregistré.</p>
        ) : (
          <div className="table-container">
            <table className="ao-table">
              <thead>
                <tr>
                  <th>N° AO</th>
                  <th>Objet</th>
                  <th>Prix HT</th>
                  <th>Quantité</th>
                  <th>Montant HT</th>
                  <th>Unité</th>
                  <th>Entité</th>
                  <th>Date publication</th>
                </tr>
              </thead>
              <tbody>
                {appelOffres.map((ao) => {
                  const prix = Number(ao.prixHT || 0);
                  const qte = Number(ao.quantite || 0);
                  const montant = prix && qte ? (prix * qte).toFixed(2) : '';
                  return (
                    <tr key={ao.id}>
                      <td>{ao.numeroAO || '-'}</td>
                      <td style={{minWidth: '280px'}}>{ao.objet || '-'}</td>
                      <td>{ao.prixHT ?? ''}</td>
                      <td>{ao.quantite ?? ''}</td>
                      <td>{montant}</td>
                      <td>{ao.unite ?? ''}</td>
                      <td>{ao.entite ?? ''}</td>
                      <td>{ao.datePublication || '-'}</td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}