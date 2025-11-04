import React, { useState, useEffect } from "react";
import api from "../api/axios";
import "../styles/addPrestataire.css";

export default function AddPrestataire() {
  const [formData, setFormData] = useState({
    nom: "",
    email: "",
    telephone: "",
  });

  const [prestataires, setPrestataires] = useState([]);
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
   const handleBack = () => window.history.back();

  // Charger la liste des prestataires (optionnel)
  useEffect(() => {
    const fetchPrestataires = async () => {
      try {
        const { data } = await api.get("/api/prestataires");
        setPrestataires(data);
      } catch (err) {
        console.error("Erreur lors du chargement :", err);
      }
    };
    fetchPrestataires();
  }, []);

  // Gérer les changements dans le formulaire
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  // Soumission du formulaire
  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");
    setError("");

    try {
      const response = await api.post("/api/prestataires", formData);
      // ✅ Corriger: utiliser response.data.prestataire au lieu de response.data
      const prestataire = response.data.prestataire || response.data;
      setMessage("✅ Prestataire ajouté avec succès !");
      setFormData({ nom: "", email: "", telephone: "" });

      // Rafraîchir la liste avec le bon objet
      setPrestataires((prev) => [...prev, prestataire]);
    } catch (err) {
      console.error("Erreur:", err);
      // ✅ Améliorer la gestion d'erreur
      const errorMessage = err.response?.data?.error || 
                          err.response?.data?.errors?.[0] ||
                          err.message ||
                          "Erreur lors de l'ajout du prestataire";
      setError(errorMessage);
    }
  };

  return (
    <div className="page-container">
      <button type="button" className="btn-back global-back" onClick={handleBack}>
        ⬅ Retour
      </button> 
    <div className="addprest-container">
      <h2>Ajouter un Prestataire</h2>
      <p>Remplissez les informations ci-dessous pour enregistrer un prestataire.</p>

      {message && <div className="success">{message}</div>}
      {error && <div className="error">{error}</div>}

      <form className="prestataire-form" onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Nom du prestataire :</label>
          <input
            type="text"
            name="nom"
            value={formData.nom}
            onChange={handleChange}
            required
            placeholder="Ex: Société XYZ"
          />
        </div>

        <div className="form-group">
          <label>Email :</label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
            placeholder="exemple@email.com"
          />
        </div>

        <div className="form-group">
          <label>Téléphone :</label>
          <input
            type="text"
            name="telephone"
            value={formData.telephone}
            onChange={handleChange}
            required
            placeholder="06XXXXXXXX"
          />
        </div>

        <button type="submit" className="btn-submit">➕ Ajouter</button>
      </form>

      {/* Tableau des prestataires */}
      {prestataires.length > 0 && (
        <div className="prestataires-list">
          <h3>Liste des Prestataires</h3>
          <table>
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
