-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_config]

AS
BEGIN
	SET NOCOUNT ON;

    
	SELECT 
		   c.MinutosMaxReserva_Sala,
		   c.MinutosMaxReserva_Domicilio,
		   c.NumeroMaxMateriales,
		   c.NumeroMaxRechazos,
		   c.MinutosBloqueoTemporal,
		   c.cRutaPathPortadas
	FROM bib.configuraciones AS c
	
	RETURN 1
END
GO
