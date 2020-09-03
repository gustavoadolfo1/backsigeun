
-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_dias_prestamo]

AS
BEGIN
	
	SET NOCOUNT ON;

	SELECT ROW_NUMBER() OVER(ORDER BY dp.iDiasPrestamoId) AS N_,
	  dp.iDiasPrestamoId,
      dp.cDescriDiasPrestamos,
      dp.cTipo_Persona,
      dp.iNumeroDias,
      dp.bHabilitado
     
	FROM bib.dias_prestamo AS dp
	
	RETURN 1
END
GO
