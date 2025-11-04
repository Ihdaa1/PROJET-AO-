import React, { useState, useEffect } from "react";
import api from "../api/axios";
import "../styles/addAO.css";
import { useNavigate } from "react-router-dom";

export default function AddAO() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    numeroAO: "",
    datePublication: "",
    objet: "",
    entiteId: "",
    responsable: "",
    designation: "",
    unite: "Unité",
    prixHT: "",
    quantite: "",
  });

  const [step, setStep] = useState(1);
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const [entites, setEntites] = useState([]);

  useEffect(() => {
    // charger la liste des entités pour l'étape 2
    const fetchEntites = async () => {
      try {
        const { data } = await api.get("/api/entites");
        setEntites(Array.isArray(data) ? data : []);
      } catch (e) {
        // en cas d'erreur, laisser la liste vide
        setEntites([]);
      }
    };
    fetchEntites();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleNext = () => setStep((prev) => prev + 1);
  const handlePrevious = () => setStep((prev) => prev - 1);
  const handleBack = () => window.history.back();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");
    setError("");

    try {
      // validation minimale côté client si l'utilisateur a sauté des étapes
      if (!formData.numeroAO || !formData.datePublication || !formData.objet) {
        setError("Veuillez remplir le numéro, la date et l'objet");
        setStep(1);
        return;
      }
      if (!formData.entiteId) {
        setError("Veuillez sélectionner une entité");
        setStep(2);
        return;
      }

      const payload = {
        numeroAO: formData.numeroAO,
        datePublication: formData.datePublication,
        objet: formData.objet,
        entiteId: Number(formData.entiteId),
        designation: formData.designation || undefined,
        unite: formData.unite || undefined,
        prixHT: formData.prixHT !== "" ? Number(formData.prixHT) : undefined,
        quantite: formData.quantite !== "" ? Number(formData.quantite) : undefined,
      };

      const response = await api.post("/api/appel-offres", payload);
      const appelOffre = response.data.appelOffre || response.data;
      
      setMessage("✅ Appel d'Offre ajouté avec succès !");
      
      // Rediriger vers la liste après 1.5 secondes
      setTimeout(() => {
        navigate("/liste-ao");
      }, 1500);
      
    } catch (err) {
      console.error("Erreur:", err);
      const errorMessage = err.response?.data?.error || 
                          err.response?.data?.errors?.[0] ||
                          err.message ||
                          "Erreur lors de l'ajout de l'appel d'offre";
      setError(errorMessage);
    }
  };

  return (
    <div className="page-container">
      <button type="button" className="btn-back global-back" onClick={handleBack}>
        ⬅ Retour
      </button>

      <div className="addao-container">
        <h2>Ajouter un Appel d'Offre</h2>
        <p>Remplissez les informations ci-dessous pour créer un nouvel AO.</p>

        {message && <div className="success">{message}</div>}
        {error && <div className="error">{error}</div>}

        <form className="ao-form" onSubmit={handleSubmit}>
          {/* Étape 1 */}
          {step === 1 && (
            <>
              <div className="form-group">
                <label>Numéro AO :</label>
                <input
                  type="text"
                  name="numeroAO"
                  value={formData.numeroAO}
                  onChange={handleChange}
                  required
                />
              </div>
              <div className="form-group">
                <label>Date de publication :</label>
                <input
                  type="date"
                  name="datePublication"
                  value={formData.datePublication}
                  onChange={handleChange}
                  required
                />
              </div>
              <div className="form-group">
                <label>Objet :</label>
                <input
                  type="text"
                  name="objet"
                  value={formData.objet}
                  onChange={handleChange}
                  required
                />
              </div>
              <button type="button" className="btn-next" onClick={handleNext}>
                ➡ Suivant
              </button>
            </>
          )}

          {/* Étape 2 */}
          {step === 2 && (
            <>
              <div className="form-group">
                <label>Entité :</label>
                <select
                  name="entiteId"
                  value={formData.entiteId}
                  onChange={handleChange}
                  required
                >
                  <option value="">-- Sélectionner une entité --</option>
                  {entites.map((e) => (
                    <option key={e.id} value={e.id}>{e.nom}</option>
                  ))}
                </select>
              </div>

              <div className="form-group">
                <label>Responsable :</label>
                <input
                  type="text"
                  name="responsable"
                  value={(entites.find(e => String(e.id) === String(formData.entiteId))?.responsable) || ""}
                  readOnly
                  placeholder="Auto depuis l'entité"
                />
              </div>

              <div className="form-group">
                <label>Désignation :</label>
                <textarea
                  name="designation"
                  rows="3"
                  value={formData.designation}
                  onChange={handleChange}
                ></textarea>
              </div>

              <div className="form-buttons">
                <button type="button" className="btn-back" onClick={handlePrevious}>
                  ⬅ Précédent
                </button>
                <button type="button" className="btn-next" onClick={handleNext}>
                  ➡ Suivant
                </button>
              </div>
            </>
          )}

          {/* Étape 3 */}
          {step === 3 && (
            <>
              <div className="form-row">
                <div className="form-group">
                  <label>Unité :</label>
                  <select
                    name="unite"
                    value={formData.unite}
                    onChange={handleChange}
                  >
                    <option value="Semestre">Semestre</option>
                    <option value="Annuel">Annuel</option>
                    <option value="Forfait">Forfait</option>
                    <option value="Unité">Unité</option>
                  </select>
                </div>

                <div className="form-group">
                  <label>Prix HT :</label>
                  <input
                    type="number"
                    name="prixHT"
                    value={formData.prixHT}
                    onChange={handleChange}
                    min="0"
                    step="0.01"
                  />
                </div>

                <div className="form-group">
                  <label>Quantité :</label>
                  <input
                    type="number"
                    name="quantite"
                    value={formData.quantite}
                    onChange={handleChange}
                    min="1"
                  />
                </div>
              </div>

              <div className="form-buttons">
                <button type="button" className="btn-back" onClick={handlePrevious}>
                  ⬅ Précédent
                </button>
                <button type="submit" className="btn-submit">
                  ➕ Ajouter l'AO
                </button>
              </div>
            </>
          )}
        </form>
      </div>
    </div>
  );
}