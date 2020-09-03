-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_UPD_config]
	@_MinutosMaxReserva_Salas INTEGER,
	@_MinutosMaxReserva_Domicilio INTEGER,
	@_NumeroMaxMateriales INTEGER,
	@_NumeroMaxRechazos INTEGER,
	@_MinutosBloqueoTemporal INTEGER,
	@_MaximoTopRanking INTEGER,
	@_cRutaPathPortadas VARCHAR(30)

AS
BEGIN
	
	UPDATE bib.configuraciones
	SET 
		MinutosMaxReserva_Sala=@_MinutosMaxReserva_Salas,
		MinutosMaxReserva_Domicilio=@_MinutosMaxReserva_Domicilio,
		NumeroMaxMateriales=@_NumeroMaxMateriales,
		NumeroMaxRechazos=@_NumeroMaxRechazos,
		MinutosBloqueoTemporal=@_MinutosBloqueoTemporal,
		MaximoTopRanking=@_MaximoTopRanking,
		cRutaPathPortadas=@_cRutaPathPortadas
	
	WHERE cRutaPathPortadas=@_cRutaPathPortadas
	IF @@ERROR<>0 GOTO ErrorCapturado
	
	COMMIT TRANSACTION
    SELECT 1 AS iResult
	RETURN 1
ErrorCapturado:
	ROLLBACK TRANSACTION
    SELECT 0 AS iResult
	RETURN 0
END
GO
